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
     * Store a newly created resource in storage.
     */
    public function store(StoreClientFormRequest $request): JsonResponse
    {
        $createdData = $this->clientService->create($request->only(["email", "name"]));
        return \response()->json($createdData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): JsonResponse
    {
        $client = $request->user();
        return \response()->json([
            "client" => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): JsonResponse
    {
        $client = $request->user();
        $this->clientService->destroy($client);
        return \response()->json(null, 204);
    }
}
