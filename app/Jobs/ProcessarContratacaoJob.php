<?php

namespace App\Jobs;

use App\Enums\StatusAnalise;
use App\Models\AnaliseCredito;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Finaliza de forma assíncrona a contratação: efetiva "contratado" e registra
 * o log. Requer QUEUE_CONNECTION=database e um worker (php artisan queue:work).
 */
class ProcessarContratacaoJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $analiseId) {}

    public function handle(): void
    {
        $analise = AnaliseCredito::find($this->analiseId);

        if (! $analise) {
            Log::warning("ProcessarContratacaoJob: análise {$this->analiseId} não encontrada.");

            return;
        }

        if ($analise->status !== StatusAnalise::PROCESSANDO_CONTRATACAO) {
            Log::info(
                "ProcessarContratacaoJob: análise {$analise->id} ignorada — status atual "
                ."\"{$analise->status->value}\" (esperado \"processando_contratacao\").",
            );

            return;
        }

        $analise->update(['status' => StatusAnalise::CONTRATADO]);

        Log::info(
            "Contratação finalizada com sucesso para a análise {$analise->id} "
            ."(cliente {$analise->cliente_id}, valor R$ {$analise->valor_solicitado}, "
            ."parcela R$ {$analise->valor_parcela}).",
        );
    }
}
