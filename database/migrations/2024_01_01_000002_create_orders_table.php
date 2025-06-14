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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique()->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'processing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_method', ['cash', 'midtrans'])->default('cash')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->nullable();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
