<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            if (!Schema::hasColumn('servicio', 'iva_percent')) {
                $table->decimal('iva_percent', 5, 2)->default(0)->after('precio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('servicio', function (Blueprint $table) {
            if (Schema::hasColumn('servicio', 'iva_percent')) {
                $table->dropColumn('iva_percent');
            }
        });
    }
};


