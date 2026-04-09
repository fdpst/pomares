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
        Schema::table('recibo', function (Blueprint $table) {
            $table->unsignedBigInteger("serie_id")
                ->after('cliente_id')
                ->nullable();
            $table->foreign("serie_id", "foreign_recibo_serie")
                ->references("id")
                ->on("invoice_series");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recibo', function (Blueprint $table) {
            $table->dropForeign("foreign_recibo_serie");
            $table->dropColumn("serie_id");
        });
    }
};
