<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('stock_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_needed', 8, 2); // Amount of ingredient needed per menu item
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_ingredients');
    }
};
