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
        Schema::create('apunte_contable_linea', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cuenta_contable_id')->nullable();
            $table->integer('apunte_contable_id')->nullable();
            $table->string('documento', 100)->nullable();
            $table->string('descripcion')->nullable();
            $table->float('debe', 10, 0)->nullable();
            $table->float('haber', 10, 0)->nullable();
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
        Schema::dropIfExists('apunte_contable_linea');
    }
};
