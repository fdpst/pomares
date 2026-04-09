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
        Schema::create('apunte_contable_importe', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('id_apunte')->nullable();
            $table->integer('iva')->nullable();
            $table->double('subtotal')->nullable();
            $table->double('importe_iva')->nullable();
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
        Schema::dropIfExists('apunte_contable_importe');
    }
};
