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
        Schema::create('user_metodo_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('pago_uno', 40)->nullable();
            $table->boolean('pago_uno_activo')->default(false);
            $table->string('pago_dos', 40)->nullable();
            $table->boolean('pago_dos_activo')->default(false);
            $table->string('pago_tres', 40)->nullable();
            $table->boolean('pago_tres_activo')->default(false);
            $table->string('pago_cuatro', 40)->nullable();
            $table->boolean('pago_cuatro_activo')->default(false);
            $table->string('pago_cinco', 40)->nullable();
            $table->boolean('pago_cinco_activo')->default(false);
            $table->string('predeterminado', 40)->nullable();
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
        Schema::dropIfExists('user_metodo_pago');
    }
};
