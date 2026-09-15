<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use Illuminate\Database\Seeder;

class LaundrySeeder extends Seeder
{
    public function run(): void
    {
        Service::create(['name' => 'Cuci Lipat', 'unit' => 'kg', 'price' => 6000]);
        Service::create(['name' => 'Cuci Setrika', 'unit' => 'kg', 'price' => 8000]);
        Service::create(['name' => 'Bed Cover', 'unit' => 'pcs', 'price' => 25000]);

        Customer::factory()->count(5)->create();
    }
}