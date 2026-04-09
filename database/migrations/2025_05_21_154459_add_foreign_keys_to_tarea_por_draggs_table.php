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
        Schema::table('tarea_por_draggs', function (Blueprint $table) {
            $table->foreign(['drag_id'])->references(['id'])->on('draggable_lists')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['tarea_id'])->references(['id'])->on('gestor_tareas')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarea_por_draggs', function (Blueprint $table) {
            $table->dropForeign('tarea_por_draggs_drag_id_foreign');
            $table->dropForeign('tarea_por_draggs_tarea_id_foreign');
        });
    }
};
