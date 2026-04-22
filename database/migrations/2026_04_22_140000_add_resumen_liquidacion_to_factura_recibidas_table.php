<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->string('resumen_liquidacion', 255)->nullable()->after('imagen');
        });
    }

    public function down(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->dropColumn('resumen_liquidacion');
        });
    }
};
