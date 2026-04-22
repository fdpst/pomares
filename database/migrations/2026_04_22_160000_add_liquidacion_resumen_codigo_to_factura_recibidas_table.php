<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->string('liquidacion_resumen_codigo', 32)->nullable()->after('resumen_liquidacion');
        });
    }

    public function down(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->dropColumn('liquidacion_resumen_codigo');
        });
    }
};
