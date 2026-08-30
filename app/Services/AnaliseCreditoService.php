<?php

namespace App\Services;

use App\Enums\StatusAnalise;
use App\Jobs\ProcessarContratacaoJob;
use App\Models\AnaliseCredito;
use App\Models\Cliente;

/**
 * Regras de negócio da análise de crédito. Os controllers só validam a
 * entrada e formatam a resposta.
 */
class AnaliseCreditoService
{
    public const RENDA_MINIMA = 1500.00;

    public const SCORE_MINIMO = 400;

    public const SCORE_TAXA_REDUZIDA = 700;

    public const TAXA_SCORE_ALTO = 2.9;

    public const TAXA_SCORE_MEDIO = 4.5;

    public const PARCELAS = 12;

    public const COMPROMETIMENTO_MAXIMO = 0.30;

    public function __construct(private readonly BureauCreditoService $bureau) {}

    /**
     * @param  array<string, mixed>  $dados  nome, cpf, renda_mensal, tipo_credito, valor_solicitado
     */
    public function solicitar(array $dados): AnaliseCredito
    {
        $cpf = preg_replace('/\D/', '', (string) $dados['cpf']);

        $cliente = Cliente::firstOrCreate(
            ['cpf' => $cpf],
            [
                'nome' => $dados['nome'],
                'renda_mensal' => $dados['renda_mensal'],
            ],
        );

        $analise = $cliente->analises()->create([
            'cpf' => $cpf,
            'nome' => $dados['nome'],
            'renda_mensal' => $dados['renda_mensal'],
            'tipo_credito' => $dados['tipo_credito'],
            'valor_solicitado' => $dados['valor_solicitado'],
            'status' => StatusAnalise::PENDENTE,
        ]);

        // Em caso de falha do Bureau a exceção sobe: a análise fica "pendente"
        // e o handler devolve 503.
        $score = $this->bureau->consultarScore($cpf);

        $this->avaliar($analise, $score);

        return $analise->refresh();
    }

    public function contratar(AnaliseCredito $analise): AnaliseCredito
    {
        $analise->update(['status' => StatusAnalise::PROCESSANDO_CONTRATACAO]);

        ProcessarContratacaoJob::dispatch($analise->id);

        return $analise->refresh();
    }

    /**
     * Ordem de precedência do desafio: renda mínima, score mínimo, faixa de
     * taxa e comprometimento. A primeira condição que falha define o motivo.
     */
    private function avaliar(AnaliseCredito $analise, int $score): void
    {
        $analise->score = $score;

        $renda = (float) $analise->renda_mensal;
        $valor = (float) $analise->valor_solicitado;

        if ($renda < self::RENDA_MINIMA) {
            $this->reprovar($analise, 'Renda mínima insuficiente');

            return;
        }

        if ($score < self::SCORE_MINIMO) {
            $this->reprovar($analise, 'Score de crédito muito baixo');

            return;
        }

        $taxa = $score >= self::SCORE_TAXA_REDUZIDA
            ? self::TAXA_SCORE_ALTO
            : self::TAXA_SCORE_MEDIO;

        $parcela = $this->calcularParcela($valor, $taxa);

        $analise->taxa_juros = $taxa;
        $analise->valor_parcela = $parcela;

        if ($parcela > $renda * self::COMPROMETIMENTO_MAXIMO) {
            $this->reprovar($analise, 'Comprometimento de renda superior a 30%');

            return;
        }

        $analise->status = StatusAnalise::APROVADO;
        $analise->motivo_rejeicao = null;
        $analise->save();
    }

    /** Juros simples sobre o valor solicitado, diluídos em 12 parcelas fixas. */
    private function calcularParcela(float $valor, float $taxaAoMes): float
    {
        $jurosTotais = $valor * ($taxaAoMes / 100) * self::PARCELAS;

        return round(($valor + $jurosTotais) / self::PARCELAS, 2);
    }

    private function reprovar(AnaliseCredito $analise, string $motivo): void
    {
        $analise->status = StatusAnalise::REPROVADO;
        $analise->motivo_rejeicao = $motivo;
        $analise->save();
    }
}
