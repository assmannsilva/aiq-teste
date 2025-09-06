<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientFormRequest;
use App\Http\Requests\UpdateClientFormRequest;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientsController extends Controller
{

    public function __construct(
        private ClientService $clientService
    ) {}

    /**
     * @OA\Post(
     *   path="/clients",
     *   tags={"Clients"},
     *   summary="Cria um novo cliente",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"name","email"},
     *       @OA\Property(property="name", type="string", example="Cauê Assmann Silva"),
     *       @OA\Property(property="email", type="string", example="caue@gmail.com")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Cliente criado com sucesso", @OA\JsonContent(ref="#/components/schemas/CreateClientResponse")),
     *   @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function store(StoreClientFormRequest $request): JsonResponse
    {
        $createdData = $this->clientService->create($request->only(["email", "name"]));
        return \response()->json($createdData, 201);
    }

    /**
     * @OA\Get(
     *   path="/clients/me",
     *   summary="Exibe o cliente autenticado",
     *   tags={"Clients"},
     *   security={{"sanctum": {}}},
     *   @OA\Response(
     *     response=200,
     *     description="Dados do cliente autenticado",
     *     @OA\JsonContent(
     *       @OA\Property(property="client", ref="#/components/schemas/Client")
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Não autenticado"
     *   )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $client = $request->user();
        return \response()->json([
            "client" => $client
        ]);
    }

    /**
     * @OA\Patch(
     *   path="/clients/me",
     *   summary="Atualiza os dados do cliente autenticado",
     *   tags={"Clients"},
     *   security={{"sanctum": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Cauê Assmann Atualizado"),
     *       @OA\Property(property="email", type="string", format="email", example="novoemail@gmail.com")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Cliente atualizado com sucesso",
     *     @OA\JsonContent(
     *       @OA\Property(property="client", ref="#/components/schemas/Client")
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Erro de validação"
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Não autenticado"
     *   )
     * )
     */
    public function update(UpdateClientFormRequest $request): JsonResponse
    {
        $client = $request->user();
        $client = $this->clientService->update($client, $request->only(["email", "name"]));
        return \response()->json([
            "client" => $client
        ]);
    }

    /**
     * @OA\Delete(
     *   path="/clients/me",
     *   summary="Remove o cliente autenticado",
     *   tags={"Clients"},
     *   security={{"sanctum": {}}},
     *   @OA\Response(
     *     response=204,
     *     description="Cliente removido com sucesso (sem conteúdo)"
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Não autenticado"
     *   )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $client = $request->user();
        $this->clientService->destroy($client);
        return \response()->json([], 204);
    }
}
