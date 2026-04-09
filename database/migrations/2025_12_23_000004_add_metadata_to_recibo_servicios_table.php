<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('recibo_servicios')) {
            return;
        }

        Schema::table('recibo_servicios', function (Blueprint $table) {
            $table->json('metadata')->nullable()->after('importe_iva')->comment('Datos adicionales de columnas dinámicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('recibo_servicios')) {
            return;
        }

        Schema::table('recibo_servicios', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};

