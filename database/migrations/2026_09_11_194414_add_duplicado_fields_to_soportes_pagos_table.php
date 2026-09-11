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
        Schema::table('soportes_pagos', function (Blueprint $table) {
            $table->foreignId('duplicado_de_id')
                ->nullable()
                ->after('estado')
                ->constrained('soportes_pagos')
                ->nullOnDelete();
            $table->string('duplicado_motivo', 255)->nullable()->after('duplicado_de_id');
            $table->timestamp('duplicado_at')->nullable()->after('duplicado_motivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soportes_pagos', function (Blueprint $table) {
            $table->dropForeign(['duplicado_de_id']);
            $table->dropColumn(['duplicado_de_id', 'duplicado_motivo', 'duplicado_at']);
        });
    }
};
