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
        Schema::create('albaranes_enviados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('albaranes_enviados_user_id_foreign');
            $table->unsignedBigInteger('cliente_id')->nullable()->index('albaranes_enviados_cliente_id_foreign');
            $table->date('fecha')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('cantidad')->nullable();
            $table->decimal('precio', 10)->nullable();
            $table->decimal('importe', 10)->nullable();
            $table->string('nro_factura')->nullable();
            $table->integer('id_anio')->nullable()->default(4);
            $table->longText('url')->nullable();
            $table->timestamps();
            $table->longText('json_recibo')->nullable();
            $table->string('contabilizado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albaranes_enviados');
    }
};
