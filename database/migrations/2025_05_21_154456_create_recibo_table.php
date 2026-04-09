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
        Schema::create('recibo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('recibo_user_id_foreign');
            $table->integer('cliente_id')->nullable();
            $table->date('fecha');
            $table->dateTime('fecha_tope')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('observaciones', 1500)->nullable();
            $table->double('sub_total');
            $table->double('iva');
            $table->integer('tipo_iva')->default(21);
            $table->tinyInteger('tipo_descuento')->default(0);
            $table->double('descuento')->default(0);
            $table->double('total_descuento');
            $table->double('total');
            $table->boolean('has_iva')->default(true);
            $table->string('presupuesto_url', 90)->nullable();
            $table->string('factura_url', 90)->nullable();
            $table->string('nota_url', 90)->nullable();
            $table->string('orden_url', 90)->nullable();
            $table->string('metodo_pago', 11)->nullable();
            $table->string('detalle_pago', 40)->nullable();
            $table->boolean('pagado')->nullable();
            $table->boolean('enviado')->nullable()->default(false);
            $table->boolean('recurrente')->nullable();
            $table->timestamps();
            $table->double('porcentaje_descuento')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recibo');
    }
};
