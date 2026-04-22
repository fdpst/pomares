<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El CIF/NIF/NIE y equivalentes intracomunitarios superan 9 caracteres;
     * antes el guardado de ficha empresa fallaba en silencio (null) y ahora
     * MySQL rechazaba el UPDATE (500).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cif', 32)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cif', 9)->nullable()->change();
        });
    }
};
