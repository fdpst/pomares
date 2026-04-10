<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidaciones_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('liquidacion_id')->nullable();
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

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones_items');
    }
};
