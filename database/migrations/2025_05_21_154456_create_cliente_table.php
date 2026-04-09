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
        Schema::create('cliente', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nro_cliente')->nullable();
            $table->unsignedBigInteger('user_id')->index('cliente_user_id_foreign');
            $table->string('nombre', 80);
            $table->string('nombre_comercial', 100)->nullable();
            $table->string('dni', 30);
            $table->string('email', 60)->nullable();
            $table->string('telefono', 30)->nullable();
            $table->text('direccion')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->integer('provincia_id')->nullable();
            $table->integer('pais_id')->nullable();
            $table->string('localidad', 45)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('contacto_nombre', 80)->nullable();
            $table->string('contacto_telefono', 30)->nullable();
            $table->string('cuenta', 100)->nullable();
            $table->string('banco', 100)->nullable();
            $table->integer('forma_pago_id')->nullable();
            $table->tinyInteger('activo')->nullable();
            $table->date('fecha_alta')->nullable();
            $table->integer('id_cuenta_contable')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('cliente');
    }
};
