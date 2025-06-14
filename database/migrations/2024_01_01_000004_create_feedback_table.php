<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('feedback', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Penjual yang diberi feedback
        $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null'); // Pemberi feedback (opsional)
        $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null'); // Pesanan terkait (opsional)
        $table->unsignedTinyInteger('rating')->nullable(); // Rating 1-5
        $table->text('comment')->nullable(); // Isi feedback
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
