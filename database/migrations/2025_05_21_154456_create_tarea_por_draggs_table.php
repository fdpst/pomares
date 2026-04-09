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
        Schema::create('tarea_por_draggs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('drag_id')->index('tarea_por_draggs_drag_id_foreign');
            $table->unsignedBigInteger('tarea_id')->index('tarea_por_draggs_tarea_id_foreign');
            $table->string('status');
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
        Schema::dropIfExists('tarea_por_draggs');
    }
};
