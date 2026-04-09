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
        Schema::table('gestor_tareas', function (Blueprint $table) {
            $table->foreign(['status_id'])->references(['id'])->on('status_tareas')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gestor_tareas', function (Blueprint $table) {
            $table->dropForeign('gestor_tareas_status_id_foreign');
        });
    }
};
