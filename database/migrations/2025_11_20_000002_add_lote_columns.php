<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('recibo', 'lote')) {
            Schema::table('recibo', function (Blueprint $table) {
                $table->string('lote')->nullable()->after('observaciones');
            });
        }

        if (!Schema::hasColumn('recibo_servicios', 'lote')) {
            Schema::table('recibo_servicios', function (Blueprint $table) {
                $table->string('lote')->nullable()->after('descripcion');
            });
        }
    }

    public function down(): void
    {
        Schema::table('recibo', function (Blueprint $table) {
            if (Schema::hasColumn('recibo', 'lote')) {
                $table->dropColumn('lote');
            }
        });

        Schema::table('recibo_servicios', function (Blueprint $table) {
            if (Schema::hasColumn('recibo_servicios', 'lote')) {
                $table->dropColumn('lote');
            }
        });
    }
};


