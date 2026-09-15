<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->decimal('price_per_kg', 12, 2);
            $table->integer('estimated_days')->default(1);
            $table->timestamps();
        });
    }

};