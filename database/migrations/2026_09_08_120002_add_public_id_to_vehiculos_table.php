<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Vehiculo;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->uuid('public_id')->nullable()->after('id');
        });

        Vehiculo::query()->select('id')->each(function (Vehiculo $vehiculo): void {
            $vehiculo->forceFill(['public_id' => (string) Str::uuid()])->saveQuietly();
        });

        Schema::table('vehiculos', function (Blueprint $table) {
            $table->unique('public_id');
        });
    }

    public function down(): void
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropUnique(['public_id']);
            $table->dropColumn('public_id');
        });
    }
};