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
        Schema::create('nro_nota', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_anio')->default(1);
            $table->unsignedBigInteger('user_id')->index('nro_nota_user_id_foreign');
            $table->integer('recibo_id');
            $table->integer('nro_nota');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nro_nota');
    }
};
