<?php

namespace App\Http\Resources;

use App\Enums\StatusAnalise;
use App\Models\AnaliseCredito;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AnaliseCredito
 */
class AnaliseCreditoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $comprometimento = $this->valor_parcela && $this->renda_mensal > 0
            ? round(($this->valor_parcela / $this->renda_mensal) * 100, 1)
            : null;

        return [
            'id' => $this->id,
            'cliente_id' => $this->cliente_id,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'renda_mensal' => $this->renda_mensal,
            'tipo_credito' => $this->tipo_credito,
            'valor_solicitado' => $this->valor_solicitado,
            'status' => $this->status,
            'score' => $this->score,
            'taxa_juros' => $this->taxa_juros,
            'valor_parcela' => $this->valor_parcela,
            'motivo_rejeicao' => $this->motivo_rejeicao,
            'comprometimento_renda_pct' => $comprometimento,
            'simulacao_url' => $this->status === StatusAnalise::APROVADO
                ? url("/simulacao/{$this->id}")
                : null,
            'criado_em' => $this->created_at,
        ];
    }
}
