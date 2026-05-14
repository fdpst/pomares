<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->foreignId('factura_recibida_id')
                ->nullable()
                ->after('total')
                ->constrained('factura_recibidas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropForeign(['factura_recibida_id']);
        });
    }
};
