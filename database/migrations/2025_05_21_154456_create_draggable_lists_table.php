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
        Schema::create('draggable_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('draggable_lists_user_id_foreign');
            $table->string('drag')->comment('Nombre del Drag o se puede tomar como un status de la tarea');
            $table->boolean('newTask')->comment('true:mostrar card para crear nueva tarea false:cerrar card');
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
        Schema::dropIfExists('draggable_lists');
    }
};
