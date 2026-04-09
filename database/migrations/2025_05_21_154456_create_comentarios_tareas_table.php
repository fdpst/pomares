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
        Schema::create('comentarios_tareas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('comentarios_tareas_user_id_foreign');
            $table->unsignedBigInteger('tarea_id')->index('comentarios_tareas_tarea_id_foreign');
            $table->text('comentario')->comment('Texto o nota que los usuarios podran agregar, luego que la tarea haya sido creada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentarios_tareas');
    }
};
