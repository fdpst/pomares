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
        if (Schema::hasTable('invoice_series')) {
            return;
        }

        Schema::create('invoice_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable()
                ->references("id")
                ->on("users")
                ->onDelete(null)
                ->onUpdate(null);
            $table->foreignId("recibo_id")->nullable()
                ->references("id")
                ->on("recibo")
                ->onDelete(null)
                ->onUpdate(null);
            $table->string("serie");
            $table->unique(['user_id', 'serie']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_series');
    }
};
