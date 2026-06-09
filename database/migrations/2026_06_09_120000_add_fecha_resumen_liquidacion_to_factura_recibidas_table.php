<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->date('fecha_resumen_liquidacion')->nullable()->after('liquidacion_resumen_codigo');
        });
    }

    public function down(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->dropColumn('fecha_resumen_liquidacion');
        });
    }
};
