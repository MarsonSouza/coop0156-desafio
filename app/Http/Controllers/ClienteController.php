<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ClienteController extends Controller
{
    private const PER_PAGE_PADRAO = 10;

    private const PER_PAGE_MAXIMO = 100;

    /**
     * Lista paginada. Aceita ?page=N e ?limit=N (alias ?per_page).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $limite = $request->integer('limit', $request->integer('per_page', self::PER_PAGE_PADRAO));
        $limite = $limite > 0 ? min($limite, self::PER_PAGE_MAXIMO) : self::PER_PAGE_PADRAO;

        $clientes = Cliente::withCount('analises')
            ->latest()
            ->paginate($limite)
            ->appends($request->query());

        return ClienteResource::collection($clientes);
    }

    public function store(StoreClienteRequest $request): JsonResponse
    {
        $cliente = Cliente::create($request->validated());

        return (new ClienteResource($cliente))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Cliente $cliente): ClienteResource
    {
        $cliente->loadCount('analises')
            ->load(['analises' => fn ($query) => $query->latest()]);

        return new ClienteResource($cliente);
    }

    public function update(UpdateClienteRequest $request, Cliente $cliente): ClienteResource
    {
        $cliente->update($request->validated());

        return new ClienteResource($cliente->refresh());
    }

    public function destroy(Cliente $cliente): Response
    {
        $cliente->delete();

        return response()->noContent();
    }
}
