<?php

namespace Tests\Feature;

use App\Enums\StatusAnalise;
use App\Jobs\ProcessarContratacaoJob;
use App\Models\AnaliseCredito;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AnaliseCreditoTest extends TestCase
{
    use RefreshDatabase;

    private function fakeBureau(array $body, int $status = 200): void
    {
        Http::fake([
            '*mock/bureau/*' => Http::response($body, $status),
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'nome' => 'João da Silva',
            'cpf' => '12345678901',
            'renda_mensal' => 8000,
            'tipo_credito' => 'pessoal',
            'valor_solicitado' => 10000,
        ], $overrides);
    }

    public function test_aprova_com_score_alto_e_taxa_de_2_9(): void
    {
        $this->fakeBureau(['score' => 850]);

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('data.status', StatusAnalise::APROVADO->value)
            ->assertJsonPath('data.score', 850)
            ->assertJsonPath('data.taxa_juros', '2.90');

        $this->assertSame(1123.33, (float) AnaliseCredito::first()->valor_parcela);
    }

    public function test_aprova_com_score_medio_e_taxa_de_4_5(): void
    {
        $this->fakeBureau(['score' => 550]);

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('data.status', StatusAnalise::APROVADO->value)
            ->assertJsonPath('data.taxa_juros', '4.50');
    }

    public function test_reprova_por_renda_insuficiente(): void
    {
        $this->fakeBureau(['score' => 850]);

        $response = $this->postJson('/api/analise-credito', $this->payload([
            'renda_mensal' => 1200,
            'valor_solicitado' => 1000,
        ]));

        $response->assertCreated()
            ->assertJsonPath('data.status', StatusAnalise::REPROVADO->value)
            ->assertJsonPath('data.motivo_rejeicao', 'Renda mínima insuficiente');
    }

    public function test_reprova_por_score_baixo(): void
    {
        $this->fakeBureau(['score' => 150]);

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('data.status', StatusAnalise::REPROVADO->value)
            ->assertJsonPath('data.motivo_rejeicao', 'Score de crédito muito baixo');
    }

    public function test_reprova_por_comprometimento_de_renda(): void
    {
        $this->fakeBureau(['score' => 550]);

        // renda 1600 (>= mínimo), parcela ~1283,33 -> muito acima de 30% (R$ 480).
        $response = $this->postJson('/api/analise-credito', $this->payload([
            'renda_mensal' => 1600,
            'valor_solicitado' => 10000,
        ]));

        $response->assertCreated()
            ->assertJsonPath('data.status', StatusAnalise::REPROVADO->value)
            ->assertJsonPath('data.motivo_rejeicao', 'Comprometimento de renda superior a 30%');
    }

    public function test_resiliente_a_erro_500_do_bureau(): void
    {
        $this->fakeBureau(['error' => 'Falha interna'], 500);

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertStatus(503)
            ->assertJsonStructure(['message', 'motivo']);

        // A análise foi persistida, mas permanece pendente (sem crash).
        $this->assertSame(StatusAnalise::PENDENTE, AnaliseCredito::first()->status);
    }

    public function test_resiliente_a_timeout_do_bureau(): void
    {
        Http::fake(fn () => throw new ConnectionException('Connection timed out'));

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertStatus(503);
        $this->assertSame(StatusAnalise::PENDENTE, AnaliseCredito::first()->status);
    }

    public function test_resiliente_a_resposta_malformada_sem_score(): void
    {
        $this->fakeBureau(['cpf' => '12345678901', 'status_bureau' => 'ok']);

        $response = $this->postJson('/api/analise-credito', $this->payload());

        $response->assertStatus(503);
        $this->assertSame(StatusAnalise::PENDENTE, AnaliseCredito::first()->status);
    }

    public function test_valida_dados_de_entrada(): void
    {
        $this->postJson('/api/analise-credito', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nome', 'cpf', 'renda_mensal', 'tipo_credito', 'valor_solicitado']);
    }

    public function test_cria_cliente_automaticamente_quando_cpf_e_novo(): void
    {
        $this->fakeBureau(['score' => 850]);

        $this->assertDatabaseCount('clientes', 0);

        $response = $this->postJson('/api/analise-credito', $this->payload([
            'cpf' => '999.888.777-01',
        ]));

        $response->assertCreated();

        $this->assertDatabaseHas('clientes', [
            'cpf' => '99988877701',
            'nome' => 'João da Silva',
        ]);

        $cliente = Cliente::first();
        $this->assertSame($cliente->id, AnaliseCredito::first()->cliente_id);
    }

    public function test_reaproveita_cliente_existente_pelo_cpf(): void
    {
        $this->fakeBureau(['score' => 850]);
        $cliente = Cliente::factory()->create(['cpf' => '12345678901']);

        $this->postJson('/api/analise-credito', $this->payload())->assertCreated();
        $this->postJson('/api/analise-credito', $this->payload())->assertCreated();

        $this->assertDatabaseCount('clientes', 1);
        $this->assertSame(2, $cliente->analises()->count());
    }

    public function test_contratar_enfileira_job_para_analise_aprovada(): void
    {
        Queue::fake();

        $analise = AnaliseCredito::factory()->aprovada()->create();

        $response = $this->postJson("/api/analise-credito/{$analise->id}/contratar");

        $response->assertOk()
            ->assertJsonPath('data.status', StatusAnalise::PROCESSANDO_CONTRATACAO->value);

        Queue::assertPushed(ProcessarContratacaoJob::class, fn ($job) => $job->analiseId === $analise->id);
        $this->assertSame(StatusAnalise::PROCESSANDO_CONTRATACAO, $analise->refresh()->status);
    }

    public function test_contratar_finaliza_para_contratado_apos_processar_a_fila(): void
    {
        // Sem Queue::fake() a conexão de testes é "sync": o job roda na hora.
        $analise = AnaliseCredito::factory()->aprovada()->create();

        $this->postJson("/api/analise-credito/{$analise->id}/contratar")->assertOk();

        $this->assertSame(StatusAnalise::CONTRATADO, $analise->refresh()->status);
    }

    public function test_contratar_rejeita_analise_nao_aprovada(): void
    {
        $analise = AnaliseCredito::factory()->create(['status' => StatusAnalise::PENDENTE]);

        $this->postJson("/api/analise-credito/{$analise->id}/contratar")
            ->assertStatus(422)
            ->assertJsonPath('status_atual', StatusAnalise::PENDENTE->value);
    }

    public function test_contratar_retorna_404_para_analise_inexistente(): void
    {
        $this->postJson('/api/analise-credito/999/contratar')->assertNotFound();
    }
}
