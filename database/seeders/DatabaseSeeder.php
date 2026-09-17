<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Membuat 5 data layanan
        $services = Service::factory()
            ->count(5)
            ->state(new Sequence(
                [
                    'name' => 'Cuci Kiloan',
                    'price_per_kg' => 7000,
                    'unit' => 'kg'
                ],
                [
                    'name' => 'Cuci Karpet',
                    'price_per_kg' => 15000,
                    'unit' => 'meter'
                ],
                [
                    'name' => 'Setrika Express',
                    'price_per_kg' => 6000,
                    'unit' => 'kg'
                ],
                [
                    'name' => 'Dry Cleaning',
                    'price_per_kg' => 20000,
                    'unit' => 'pcs'
                ],
                [
                    'name' => 'Cuci Selimut',
                    'price_per_kg' => 12000,
                    'unit' => 'pcs'
                ],
            ))
            ->create();

        // 2. Membuat 10 data customer
        $customers = Customer::factory()
            ->count(10)
            ->create();

        // 3. Memilih satu customer secara acak
        $chosenCustomer = $customers->random();

        // 4. Membuat satu order
        $order = Order::create([
            'customer_id' => $chosenCustomer->id,
            'invoice_code' => 'INV-' . strtoupper(Str::random(8)),
            'order_date' => now()->subDays(2),
            'completion_date' => now(),
            'status' => 'completed',
            'total_price' => 0,
        ]);

        // 5. Mengambil 2 service secara acak
        $randomServices = $services->random(2);

        $totalPrice = 0;

        // 6. Memasukkan detail order
        foreach ($randomServices as $service) {

            $qty = rand(2, 5);

            $subtotal = $service->price_per_kg * $qty;

            $totalPrice += $subtotal;

            $order->services()->attach($service->id, [
                'qty' => $qty,
                'subtotal' => $subtotal
            ]);
        }

        // 7. Update total harga order
        $order->update([
            'total_price' => $totalPrice
        ]);
    }
}
