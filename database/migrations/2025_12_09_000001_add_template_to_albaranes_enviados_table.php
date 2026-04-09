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
        Schema::table('albaranes_enviados', function (Blueprint $table) {
            if (!Schema::hasColumn('albaranes_enviados', 'template')) {
                $table->string('template', 50)->default('classic')->after('url');
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
        Schema::table('albaranes_enviados', function (Blueprint $table) {
            if (Schema::hasColumn('albaranes_enviados', 'template')) {
                $table->dropColumn('template');
            }
        });
    }
};

