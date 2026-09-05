<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfil_id')->constrained('perfil')->cascadeOnDelete();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->unsignedSmallInteger('anio')->nullable();
            $table->unsignedInteger('kilometros')->nullable();
            $table->decimal('precio', 12, 2)->nullable();
            $table->string('moneda', 3)->default('USD');
            $table->string('ubicacion')->nullable();
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('publicado')->default(true);
            $table->timestamps();
            $table->index(['tipo', 'publicado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};