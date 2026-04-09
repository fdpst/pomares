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
        Schema::table('albaranes_enviados', function (Blueprint $table) {
            $table->boolean('unificado')->default(false)->after('contabilizado');
            $table->unsignedBigInteger('albaran_unificado_id')->nullable()->after('unificado');
            $table->foreign('albaran_unificado_id')->references('id')->on('albaranes_enviados')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('albaranes_enviados', function (Blueprint $table) {
            $table->dropForeign(['albaran_unificado_id']);
            $table->dropColumn(['unificado', 'albaran_unificado_id']);
        });
    }
};
