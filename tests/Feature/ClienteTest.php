<?php

namespace Tests\Feature;

use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    private function dadosValidos(array $overrides = []): array
    {
        return array_merge([
            'nome' => 'Maria Souza',
            'cpf' => '11122233344',
            'email' => 'maria@example.com',
            'telefone' => '(51) 99999-0000',
            'renda_mensal' => 5200.00,
        ], $overrides);
    }

    public function test_cria_cliente_com_dados_validos(): void
    {
        $response = $this->postJson('/api/clientes', $this->dadosValidos());

        $response->assertCreated()
            ->assertJsonPath('data.nome', 'Maria Souza')
            ->assertJsonPath('data.cpf', '11122233344');

        $this->assertDatabaseHas('clientes', ['email' => 'maria@example.com']);
    }

    public function test_falha_ao_criar_sem_campos_obrigatorios(): void
    {
        $this->postJson('/api/clientes', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nome', 'cpf', 'email', 'renda_mensal']);
    }

    public function test_falha_ao_criar_com_cpf_invalido(): void
    {
        $this->postJson('/api/clientes', $this->dadosValidos(['cpf' => '123']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_falha_ao_criar_com_cpf_duplicado(): void
    {
        Cliente::factory()->create(['cpf' => '11122233344']);

        $this->postJson('/api/clientes', $this->dadosValidos())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }

    public function test_falha_ao_criar_com_email_duplicado(): void
    {
        Cliente::factory()->create(['email' => 'maria@example.com']);

        $this->postJson('/api/clientes', $this->dadosValidos())
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email'])
            ->assertJsonPath('errors.email.0', 'Este e-mail já está cadastrado.');
    }

    public function test_falha_ao_criar_com_cpf_duplicado_mensagem_amigavel(): void
    {
        Cliente::factory()->create(['cpf' => '11122233344']);

        $this->postJson('/api/clientes', $this->dadosValidos())
            ->assertStatus(422)
            ->assertJsonPath('errors.cpf.0', 'Este CPF já está cadastrado.');
    }

    public function test_lista_clientes_paginado(): void
    {
        Cliente::factory()->count(3)->create();

        $this->getJson('/api/clientes')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'nome', 'cpf', 'email', 'renda_mensal']],
                'links',
                'meta' => ['current_page', 'per_page', 'total'],
            ])
            ->assertJsonPath('meta.total', 3)
            ->assertJsonPath('meta.per_page', 10);
    }

    public function test_lista_respeita_page_e_limit(): void
    {
        Cliente::factory()->count(25)->create();

        $pagina1 = $this->getJson('/api/clientes?limit=10&page=1')->assertOk();
        $pagina1->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.last_page', 3);

        $this->getJson('/api/clientes?limit=10&page=3')
            ->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.current_page', 3);
    }

    public function test_limit_invalido_cai_no_padrao_de_10(): void
    {
        Cliente::factory()->count(12)->create();

        $this->getJson('/api/clientes?limit=0')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 10);
    }

    public function test_exibe_cliente_existente(): void
    {
        $cliente = Cliente::factory()->create();

        $this->getJson("/api/clientes/{$cliente->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $cliente->id);
    }

    public function test_retorna_404_para_cliente_inexistente(): void
    {
        $this->getJson('/api/clientes/999')->assertNotFound();
    }

    public function test_atualiza_parcialmente_cliente_existente(): void
    {
        $cliente = Cliente::factory()->create(['nome' => 'Nome Antigo']);

        $this->putJson("/api/clientes/{$cliente->id}", ['nome' => 'Nome Novo'])
            ->assertOk()
            ->assertJsonPath('data.nome', 'Nome Novo');

        $this->assertDatabaseHas('clientes', ['id' => $cliente->id, 'nome' => 'Nome Novo']);
    }

    public function test_atualizacao_mantem_cpf_do_proprio_cliente_valido(): void
    {
        $cliente = Cliente::factory()->create(['cpf' => '55566677788']);

        $this->putJson("/api/clientes/{$cliente->id}", ['cpf' => '55566677788', 'nome' => 'X'])
            ->assertOk();
    }

    public function test_remove_cliente_existente(): void
    {
        $cliente = Cliente::factory()->create();

        $this->deleteJson("/api/clientes/{$cliente->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    public function test_retorna_404_ao_remover_cliente_inexistente(): void
    {
        $this->deleteJson('/api/clientes/999')->assertNotFound();
    }
}
