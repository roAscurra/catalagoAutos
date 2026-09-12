<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->boolean('vendido')->default(false)->after('publicado');
            $table->boolean('mostrar_en_landing')->default(false)->after('vendido');
            $table->date('fecha_venta')->nullable()->after('mostrar_en_landing');
        });

        Schema::create('vehiculo_venta_imagenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->cascadeOnDelete();
            $table->string('ruta');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculo_venta_imagenes');

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropColumn(['vendido', 'mostrar_en_landing', 'fecha_venta']);
        });
    }
};
