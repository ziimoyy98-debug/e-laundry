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
                'Cuci Komplit Reguler',
                'Cuci Kering Express',
                'Setrika Rapih',
                'Dry Clean Jas / Gaun',
                'Cuci Bed Cover'
            ]),
            'unit' => $this->faker->randomElement(['kg', 'pcs', 'pasang']),
            'price' => $this->faker->randomElement([6000, 7000, 10000, 15000, 25000]),
            'estimated_days' => $this->faker->numberBetween(1, 3),
        ];
    }
}