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
            $table->boolean('unificado')->default(false)->after('fecha_recurrente');
            $table->unsignedBigInteger('recibo_unificado_id')->nullable()->after('unificado');
            $table->foreign('recibo_unificado_id')->references('id')->on('recibo')->onDelete('set null');
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
            $table->dropForeign(['recibo_unificado_id']);
            $table->dropColumn(['unificado', 'recibo_unificado_id']);
        });
    }
};
