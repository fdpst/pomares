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
        Schema::create('factura_recibidas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha')->nullable();
            $table->string('nro_factura', 50)->nullable();
            $table->unsignedBigInteger('user_id')->index('factura_recibidas_user_id_foreign');
            $table->unsignedBigInteger('proveedor_id')->index('factura_recibidas_proveedor_id_foreign');
            $table->integer('retencion_id')->nullable();
            $table->string('descripcion')->nullable();
            $table->double('total')->nullable();
            $table->longText('imagen')->nullable();
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
        Schema::dropIfExists('factura_recibidas');
    }
};
