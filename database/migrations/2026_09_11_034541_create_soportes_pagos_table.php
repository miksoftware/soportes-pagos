<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('soportes_pagos', function (Blueprint $table) {
            $table->id();
            $table->string('cedula', 50)->index();
            $table->string('celular', 50)->nullable();
            $table->string('archivo_path');
            $table->string('archivo_nombre_original')->nullable();
            $table->string('archivo_extension', 10)->nullable();
            $table->string('archivo_tamano', 30)->nullable();
            $table->string('tipo', 20)->default('imagen'); // imagen o pdf
            $table->string('estado', 30)->default('pendiente');
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soportes_pagos');
    }
};
