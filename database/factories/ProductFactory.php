<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    public $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fakestore_product_id' => $this->faker->unique()->numberBetween(1, 100),
            'title' => $this->faker->word(),
            'price_in_cents' => $this->faker->randomNumber(4, true),
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
