<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Cuci Kiloan',
                'Cuci Karpet',
                'Setrika Express',
                'Dry Cleaning',
                'Cuci Satuan'
            ]),

            'price_per_kg' => $this->faker->randomElement([
                7000,
                15000,
                5000,
                25000,
                10000
            ]),

            'unit' => $this->faker->randomElement([
                'kg',
                'pcs',
                'meter'
            ]),
        ];
    }
}
