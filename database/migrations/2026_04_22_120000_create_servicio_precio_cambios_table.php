<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servicio_precio_cambios', function (Blueprint $table) {
            $table->increments('id');
            // Mismo tipo que `servicio.id` (integer con signo); sin FK por compatibilidad con tablas heredadas
            $table->integer('servicio_id');
            $table->integer('user_id');
            $table->decimal('precio_anterior', 12, 4)->nullable();
            $table->decimal('precio_nuevo', 12, 4);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['servicio_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicio_precio_cambios');
    }
};
