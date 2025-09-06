<?php

use App\Services\ClientService;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;

it('creates a client successfully with token', function () {
    $data = ['name' => 'Cauê Assmann Silva', 'email' => 'caue@gmail.com'];

    $mockRepository = Mockery::mock(ClientRepository::class);
    $mockClient = Mockery::mock(Client::class)->makePartial();

    $mockRepository->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($mockClient);

    $mockClient->shouldReceive('createToken')
        ->once()
        ->with('client_api_token')
        ->andReturn(new NewAccessToken(new PersonalAccessToken, 'fake_token_123'));

    $response = new ClientService($mockRepository)->create($data);

    expect($response['client'])->toBe($mockClient);
    expect($response['token'])->toBe('fake_token_123');
});
