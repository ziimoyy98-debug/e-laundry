<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_code' => $this->invoice_code,
            'order_date' => $this->order_date ? \carbon\carbon::parse($this->order_date)->format('Y-m-d H:i:s') : null,
            'completion_date' => $this->completion_date,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'customer' => [
                'id' => $this->customer->id ?? null,
                'name' => $this->customer->name ?? null,
                'phone' => $this->customer->phone ?? null,
                'address' => $this->customer->address ?? null,
            ],
            'details' => $this->services->map(function ($service) {
                return [
                    'service_id' => $service->id,
                    'service_name' => $service->name,
                    'price_per_kg' => $service->price_per_kg,
                    'qty' => $service->pivot->qty,
                    'subtotal' => $service->pivot->subtotal,
                ];
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}

