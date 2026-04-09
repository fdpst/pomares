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
        Schema::create('factura_recibidas_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('factura_recibidas_id')->nullable();
            $table->integer('id_servicio')->nullable();
            $table->string('concepto', 100)->nullable();
            $table->integer('cantidad')->nullable();
            $table->double('precio')->nullable();
            $table->integer('dcto')->nullable();
            $table->integer('iva')->nullable();
            $table->double('total')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_recibidas_items');
    }
};
