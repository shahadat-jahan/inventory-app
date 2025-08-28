<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    protected $model = Warehouse::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->bothify('WH-###')),
            'name' => $this->faker->words(2, true) . ' Warehouse',
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
