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
        Schema::create('apunte_contable', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('fecha')->nullable();
            $table->integer('tipo_apunte_id')->nullable();
            $table->integer('apunte_predefinido_id')->nullable();
            $table->integer('cliente_id')->nullable();
            $table->integer('factura_id')->nullable();
            $table->integer('proveedor_id')->nullable();
            $table->integer('factura_recibida_id')->nullable();
            $table->string('nota')->nullable();
            $table->float('debito', 10, 0)->nullable();
            $table->float('credito', 10, 0)->nullable();
            $table->string('documento')->nullable();
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
        Schema::dropIfExists('apunte_contable');
    }
};
