<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('fecha')->nullable();
            $table->string('nro_factura', 50)->nullable();
            $table->unsignedBigInteger('user_id')->index('liquidaciones_user_id_foreign');
            $table->unsignedBigInteger('proveedor_id')->index('liquidaciones_proveedor_id_foreign');
            $table->integer('retencion_id')->nullable();
            $table->string('descripcion')->nullable();
            $table->double('total')->nullable();
            $table->longText('imagen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};
