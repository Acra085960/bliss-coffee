<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to the user who placed the order
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending'); // Status of the order
            $table->decimal('total_price', 8, 2); // Total price of the order
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending'); // Payment status
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
