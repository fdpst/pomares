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
        Schema::table('albaran_enviado_item_agregados', function (Blueprint $table) {
            $table->foreign(['albaran_enviado_id'])->references(['id'])->on('albaranes_enviados')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('albaran_enviado_item_agregados', function (Blueprint $table) {
            $table->dropForeign('albaran_enviado_item_agregados_albaran_enviado_id_foreign');
        });
    }
};
