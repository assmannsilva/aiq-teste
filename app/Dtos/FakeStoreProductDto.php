<?php

namespace App\Dtos;

// Dto criado para facilitar a transferência de dados do produto, já que não são necessariamente os mesmos dados do banco (nomes dos campos etc.)
// tem a mesma nomenclatura que a API, e retornaremos na reposta essa mesmo corpo para maior clareza
final class FakeStoreProductDto
{

    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $image,
        public readonly float $price,
        public readonly ?float $rate = null,
        public readonly ?int $ratingCount = null,
    ) {}
}
