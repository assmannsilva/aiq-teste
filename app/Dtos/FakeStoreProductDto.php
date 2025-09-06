<?php

namespace App\Dtos;


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
