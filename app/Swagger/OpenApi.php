<?php

namespace App\Swagger;

/**
 * @OA\Info(
 * version="1.0.0",
 * title="API de Produtos Favoritos",
 * description="Documentação da API desenvolvida para o desafio técnico da aiqfome",
 * @OA\Contact(
 * email="caueassmannsilva114@gmail.com"
 * )
 * )
 *
 * @OA\Server(
 * url=L5_SWAGGER_CONST_HOST,
 * description="Servidor API"
 * )
 * 
 * /**
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="Autenticação via Bearer token (use 'Bearer {token}')",
 *     name="Authorization",
 *     in="header",
 *     securityScheme="sanctum"
 * )
 */
class OpenApi {}
