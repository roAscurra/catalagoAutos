<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfil', function (Blueprint $table) {
            $table->string('plantilla', 30)->default('editorial')->after('color_principal');
            $table->string('color_secundario', 7)->default('#f3aa3c')->after('plantilla');
            $table->string('imagen_portada')->nullable()->after('color_secundario');
            $table->string('titulo_portada')->nullable()->after('imagen_portada');
            $table->text('subtitulo_portada')->nullable()->after('titulo_portada');
            $table->string('whatsapp', 30)->nullable()->after('subtitulo_portada');
            $table->string('instagram')->nullable()->after('whatsapp');
            $table->string('facebook')->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('perfil', function (Blueprint $table) {
            $table->dropColumn(['plantilla', 'color_secundario', 'imagen_portada', 'titulo_portada', 'subtitulo_portada', 'whatsapp', 'instagram', 'facebook']);
        });
    }
};