<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use RuntimeException;
use Throwable;

/**
 * Falha ao obter um score do Bureau. O render() responde 503 limpo em vez
 * de deixar virar um 500.
 */
class BureauIndisponivelException extends RuntimeException
{
    public function __construct(
        string $message = 'Serviço de Bureau de Crédito indisponível.',
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => 'Não foi possível consultar o Bureau de Crédito no momento. Tente novamente em instantes.',
            'motivo' => $this->getMessage(),
        ], 503);
    }
}
