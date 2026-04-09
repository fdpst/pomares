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
        Schema::create('albaran_enviado_item_agregados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('albaran_enviado_id')->nullable()->index('albaran_enviado_item_agregados_albaran_enviado_id_foreign');
            $table->string('descripcion')->nullable();
            $table->string('cantidad')->nullable();
            $table->decimal('precio', 10)->nullable();
            $table->decimal('importe', 10)->nullable();
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
        Schema::dropIfExists('albaran_enviado_item_agregados');
    }
};
