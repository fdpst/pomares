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
        Schema::create('recibo_servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('recibo_servicios_user_id_foreign');
            $table->integer('recibo_id');
            $table->integer('id_servicio')->nullable();
            $table->string('descripcion', 500);
            $table->double('cantidad')->nullable();
            $table->double('precio')->nullable();
            $table->double('importe')->nullable();
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
        Schema::dropIfExists('recibo_servicios');
    }
};
