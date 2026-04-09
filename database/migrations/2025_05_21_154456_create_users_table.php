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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('provincia_id')->index('users_provincia_id_foreign');
            $table->string('filetoken', 20)->nullable();
            $table->string('name');
            $table->string('nombre_fiscal')->nullable();
            $table->string('cif', 9)->nullable();
            $table->integer('telefono')->nullable();
            $table->string('ciudad', 60)->nullable();
            $table->text('direccion')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('role');
            $table->rememberToken();
            $table->timestamps();
            $table->longText('avatar')->nullable()->comment('Imagen del usuario');
            $table->string('cuenta', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
