<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
    
            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->onDelete('cascade');
    
            $table->string('invoice_code')->unique();
            $table->date('order_date');
            $table->date('completion_date')->nullable();
    
            $table->enum('status', [
                'pending',
                'processing',
                'ready',
                'completed'
            ])->default('pending');
    
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
        });
    }
    
    
    
    


};