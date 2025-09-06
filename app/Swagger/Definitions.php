<?php

namespace App\Swagger;


/**
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     @OA\Property(property="id", type="string", format="uuid", example="0199203c-b199-7119-a6b0-33ab6edfd1a7"),
 *     @OA\Property(property="name", type="string", example="Cauê Assmann Silva"),
 *     @OA\Property(property="email", type="string", format="email", example="caue2@gmail.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-06T18:14:32.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-06T18:14:32.000000Z")
 * )
 *
 * @OA\Schema(
 * schema="CreateClientResponse",
 * type="object",
 * @OA\Property(property="client", ref="#/components/schemas/Client"),
 * @OA\Property(property="token", type="string", example="1|Xs0meR4nd0mT0kenString")
 * )
 *
 *
 * @OA\Schema(
 * schema="FavoriteProduct",
 * type="object",
 * @OA\Property(property="id", type="integer", example=999),
 * @OA\Property(property="title", type="string", example="Fjallraven Backpack"),
 * @OA\Property(property="price", type="number", format="float", example=109.95),
 * @OA\Property(property="image", type="string", example="https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"),
 * @OA\Property(property="rate", type="string", nullable=true, example="4.6"),
 * @OA\Property(property="ratingCount", type="integer", nullable=true, example=400)
 * )
 */
class Definitions {}
