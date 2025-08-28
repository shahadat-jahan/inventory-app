<?php

namespace Database\Factories;

use App\Models\WorkOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        return [
            'number' => 'WO-' . $this->faker->unique()->numberBetween(1000, 9999),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->randomFloat(3, 1, 100),
            'status' => $this->faker->randomElement(['planned', 'in_progress', 'completed', 'cancelled']),
            'start_date' => $this->faker->optional()->date(),
            'completion_date' => $this->faker->optional()->date(),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
