<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('vehicle_limit')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('perfil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('nombre_negocio');
            $table->string('telefono', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('color_principal', 7)->default('#e85d04');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfil');
        Schema::dropIfExists('plans');
    }
};
