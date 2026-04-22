<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Unifica el rol almacenado: todo el mundo queda como administrador (1).
     * Los datos de empresa (nombre_fiscal, pivotes, etc.) se conservan.
     */
    public function up(): void
    {
        DB::table('users')->update(['role' => 1]);
    }

    public function down(): void
    {
        // Migración de datos: no se puede reconstruir el rol anterior.
    }
};
