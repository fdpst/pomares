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
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nro_proveedor')->nullable();
            $table->unsignedBigInteger('user_id')->index('proveedores_user_id_foreign');
            $table->string('nombre', 100);
            $table->string('email', 50)->nullable();
            $table->string('telefono', 35)->nullable();
            $table->string('cif', 20)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->string('cp', 20)->nullable();
            $table->string('localidad', 100)->nullable();
            $table->integer('id_provincia')->nullable();
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
        Schema::dropIfExists('proveedores');
    }
};
