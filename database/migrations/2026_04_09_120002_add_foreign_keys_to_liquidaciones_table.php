<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->foreign(['proveedor_id'])->references(['id'])->on('proveedores')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    public function down(): void
    {
        Schema::table('liquidaciones', function (Blueprint $table) {
            $table->dropForeign('liquidaciones_proveedor_id_foreign');
            $table->dropForeign('liquidaciones_user_id_foreign');
        });
    }
};
