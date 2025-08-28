<?php

namespace Database\Factories;

use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    protected $model = SalesOrder::class;

    public function definition(): array
    {
        return [
            'number' => 'SO-' . $this->faker->unique()->numberBetween(1000, 9999),
            'customer_id' => Customer::factory(),
            'order_date' => $this->faker->date(),
            'expected_date' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['draft', 'confirmed', 'shipped', 'delivered', 'cancelled']),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
