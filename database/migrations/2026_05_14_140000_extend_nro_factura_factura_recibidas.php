<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->string('nro_factura', 191)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('factura_recibidas', function (Blueprint $table) {
            $table->string('nro_factura', 50)->nullable()->change();
        });
    }
};
