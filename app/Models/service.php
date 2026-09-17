<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_kg',
        'unit'
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(
            Order::class,
            'order_details'
        )
        ->withPivot('qty', 'subtotal')
        ->withTimestamps();
    }
}
