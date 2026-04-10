<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedor_comisiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proveedor_id');
            $table->integer('servicio_id');
            $table->string('tipo', 20);
            $table->decimal('valor', 12, 4);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['proveedor_id', 'servicio_id']);
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->cascadeOnDelete();
            $table->foreign('servicio_id')->references('id')->on('servicio')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedor_comisiones');
    }
};
