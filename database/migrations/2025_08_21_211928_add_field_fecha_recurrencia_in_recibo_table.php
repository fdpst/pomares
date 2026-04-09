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
        Schema::table('recibo', function (Blueprint $table) {
            if (!Schema::hasColumn('recibo', 'fecha_recurrente')) {
                $table->integer('fecha_recurrente')->nullable()->after('recurrente');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recibo', function (Blueprint $table) {
            if (!Schema::hasColumn('recibo', 'fecha_recurrente')) {
                $table->dropColumn(['fecha_recurrente']);
            }
        });
    }
};
