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
        Schema::create('servicio', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('nro')->nullable();
            $table->integer('user_id');
            $table->string('descripcion')->nullable();
            $table->double('precio')->nullable();
            $table->boolean('venta')->nullable()->default(true);
            $table->integer('id_cuenta_contable')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->date('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicio');
    }
};
