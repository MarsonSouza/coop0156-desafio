<?php

namespace Database\Factories;

use App\Enums\StatusAnalise;
use App\Enums\TipoCredito;
use App\Models\AnaliseCredito;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnaliseCredito>
 */
class AnaliseCreditoFactory extends Factory
{
    protected $model = AnaliseCredito::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'cpf' => fake()->unique()->numerify('###########'),
            'nome' => fake()->name(),
            'renda_mensal' => 8000,
            'tipo_credito' => TipoCredito::PESSOAL,
            'valor_solicitado' => 10000,
            'status' => StatusAnalise::PENDENTE,
        ];
    }

    public function aprovada(): static
    {
        return $this->state(fn () => [
            'status' => StatusAnalise::APROVADO,
            'score' => 850,
            'taxa_juros' => 2.9,
            'valor_parcela' => 1123.33,
        ]);
    }

    public function reprovada(): static
    {
        return $this->state(fn () => [
            'status' => StatusAnalise::REPROVADO,
            'score' => 150,
            'motivo_rejeicao' => 'Score de crédito muito baixo',
        ]);
    }
}
