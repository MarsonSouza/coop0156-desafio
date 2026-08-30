<?php

namespace App\Services;

use App\Exceptions\BureauIndisponivelException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Cliente HTTP do Bureau externo. Qualquer falha (timeout, HTTP 5xx, corpo
 * sem "score") vira uma BureauIndisponivelException.
 */
class BureauCreditoService
{
    /**
     * @throws BureauIndisponivelException
     */
    public function consultarScore(string $cpf): int
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        $baseUrl = rtrim((string) config('services.score_bureau.url'), '/');
        $timeout = (int) config('services.score_bureau.timeout', 3);

        try {
            $response = Http::timeout($timeout)
                ->acceptJson()
                ->get("{$baseUrl}/{$cpf}");
        } catch (ConnectionException $e) {
            Log::warning('Bureau de Crédito inacessível (timeout/conexão).', [
                'cpf' => $cpf,
                'erro' => $e->getMessage(),
            ]);

            throw new BureauIndisponivelException(
                'Tempo de resposta do Bureau de Crédito excedido.',
                previous: $e,
            );
        }

        if ($response->failed()) {
            Log::warning('Bureau de Crédito retornou erro HTTP.', [
                'cpf' => $cpf,
                'status' => $response->status(),
            ]);

            throw new BureauIndisponivelException(
                "O Bureau de Crédito retornou HTTP {$response->status()} ao consultar o CPF.",
            );
        }

        $score = $response->json('score');

        if (! is_numeric($score)) {
            Log::warning('Resposta do Bureau de Crédito sem score válido.', [
                'cpf' => $cpf,
                'body' => $response->json(),
            ]);

            throw new BureauIndisponivelException(
                'Resposta do Bureau de Crédito em formato inesperado (score ausente).',
            );
        }

        return (int) $score;
    }
}
