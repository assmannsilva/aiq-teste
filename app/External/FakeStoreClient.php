<?php

namespace App\External;

use App\Dtos\FakeStoreProductDto;
use App\Exceptions\ExternalApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class FakeStoreClient
{
    public function __construct(private Client $client) {}

    /**
     * Get Product Info in FakeStore API
     * @param string $id
     * @throws ExternalApiException
     * @return FakeStoreProductDto
     */
    public function getProductById(string $id): FakeStoreProductDto
    {
        try {
            $response = $this->client->get("products/$id");
            $body = $response->getBody()->getContents();
            $json = \json_decode($body, true);
            return new FakeStoreProductDto(
                $json["id"],
                $json["title"],
                $json["image"],
                $json["price"],
                data_get($json, "rating.rate"),
                data_get($json, "rating.count"),
            );
        } catch (ClientException $clientException) {
            throw new ExternalApiException("Invalid Product Informed", 400, $clientException);
        } catch (ServerException $serverException) {
            $statusCode = $serverException->getResponse()?->getStatusCode() ?? 500;
            throw new ExternalApiException("Products API Server Error", $statusCode, $serverException);
        } catch (GuzzleException $exception) {
            throw new ExternalApiException("Products API Unreacheable", 502, $exception);
        }
    }
}
