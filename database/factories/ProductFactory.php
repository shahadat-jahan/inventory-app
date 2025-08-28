<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->bothify('PRD-####')),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'unit' => 'pcs',
            'price' => $this->faker->randomFloat(2, 1, 9999),
            'stock' => $this->faker->randomFloat(3, 0, 1000),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
