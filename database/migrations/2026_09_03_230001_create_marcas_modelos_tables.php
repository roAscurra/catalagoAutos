<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_id')->constrained('marcas')->cascadeOnDelete();
            $table->string('nombre');
            $table->string('slug');
            $table->timestamps();
            $table->unique(['marca_id', 'slug']);
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->foreignId('marca_id')->nullable()->after('tipo')->constrained('marcas')->nullOnDelete();
            $table->foreignId('modelo_id')->nullable()->after('marca_id')->constrained('modelos')->nullOnDelete();
            $table->dropColumn(['marca', 'modelo']);
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropForeign(['modelo_id']);
            $table->dropForeign(['marca_id']);
            $table->dropColumn(['modelo_id', 'marca_id']);
        });

        Schema::dropIfExists('modelos');
        Schema::dropIfExists('marcas');
    }
};