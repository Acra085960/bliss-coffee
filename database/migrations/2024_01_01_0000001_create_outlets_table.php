<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama gerobak/outlet
            $table->string('address'); // Alamat lengkap
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->decimal('lat', 10, 7)->nullable(); // Latitude (untuk Google Maps)
            $table->decimal('lng', 10, 7)->nullable(); // Longitude (untuk Google Maps)
            $table->string('phone')->nullable(); // Kontak outlet
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Penanggung jawab (opsional)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};