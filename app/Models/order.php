<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_code',
        'order_date',
        'completion_date',
        'status',
        'total_price'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(
            Service::class,
            'order_details'
        )
        ->withPivot('qty', 'subtotal')
        ->withTimestamps();
    }
}
