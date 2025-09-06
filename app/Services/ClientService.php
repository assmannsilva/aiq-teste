<?php

namespace App\Services;

use App\Models\Client;
use App\Repositories\ClientRepository;

//Service bem simples que praticamente só faz um wrapper para o ClientRepository
//Mas feito dessa maneira para facilitar testes unitários e manter a lógica de negócio separada do controller
//Para demonstrar escalabilidade e boas práticas
class ClientService
{

    public function __construct(private ClientRepository $clientRepository) {}

    /**
     * Creates the Client
     * @param array $data
     * @return array {client, token}
     */
    public function create(array $data): array
    {
        $client = $this->clientRepository->create($data);
        $token = $client->createToken("client_api_token")->plainTextToken;
        return [
            "client" => $client,
            "token" => $token,
        ];
    }

    /**
     * Updates the Client
     * @param Client $client
     * @return Client
     */
    public function update(Client $client, array $data): Client
    {
        return $this->clientRepository->update($client, $data);
    }

    /**
     * Deletes the client
     * @param Client $client
     * @return bool
     */
    public function destroy(Client $client): bool
    {
        return $this->clientRepository->delete($client);
    }
}
