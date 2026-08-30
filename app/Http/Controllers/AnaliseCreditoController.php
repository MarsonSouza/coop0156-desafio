<?php

namespace App\Http\Controllers;

use App\Enums\StatusAnalise;
use App\Http\Requests\SolicitarAnaliseRequest;
use App\Http\Resources\AnaliseCreditoResource;
use App\Models\AnaliseCredito;
use App\Services\AnaliseCreditoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AnaliseCreditoController extends Controller
{
    public function __construct(private readonly AnaliseCreditoService $service) {}

    public function solicitar(SolicitarAnaliseRequest $request): JsonResponse
    {
        $analise = $this->service->solicitar($request->validated());

        return (new AnaliseCreditoResource($analise))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function contratar(int $id): JsonResponse
    {
        $analise = AnaliseCredito::findOrFail($id);

        if ($analise->status !== StatusAnalise::APROVADO) {
            return response()->json([
                'message' => 'Somente análises com status "aprovado" podem ser contratadas.',
                'status_atual' => $analise->status->value,
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $analise = $this->service->contratar($analise);

        return response()->json([
            'message' => 'Contratação enviada para processamento.',
            'data' => new AnaliseCreditoResource($analise),
        ]);
    }
}
