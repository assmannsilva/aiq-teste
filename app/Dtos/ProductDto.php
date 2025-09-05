<?php

namespace App\Dtos;


final class ProductDto
{

    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $image,
        public readonly float $price,
        public readonly ?float $rate,
        public readonly ?int $ratingCount
    ) {}
}
