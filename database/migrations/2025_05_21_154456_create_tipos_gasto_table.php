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
        Schema::create('tipos_gasto', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre', 50);
            $table->integer('user_id');
            $table->date('created_at')->nullable();
            $table->date('deleted_at')->nullable();
            $table->date('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_gasto');
    }
};
