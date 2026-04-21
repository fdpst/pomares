<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catálogo de formas de pago por usuario (puntos de venta).
     */
    public function up(): void
    {
        if (! Schema::hasTable('catalogo_formas_pago')) {
            Schema::create('catalogo_formas_pago', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('descripcion', 120);
                $table->timestamps();

                $table->unique(['user_id', 'descripcion']);
            });
        }

        Schema::table('proveedores', function (Blueprint $table) {
            if (! Schema::hasColumn('proveedores', 'nombre_comercial')) {
                $table->string('nombre_comercial', 150)->nullable()->after('nombre');
            }
            if (! Schema::hasColumn('proveedores', 'catalogo_forma_pago_id')) {
                $table->unsignedBigInteger('catalogo_forma_pago_id')->nullable()->after('nombre_comercial');
            }
            if (! Schema::hasColumn('proveedores', 'numero_cuenta')) {
                $table->string('numero_cuenta', 50)->nullable()->after('catalogo_forma_pago_id');
            }
            if (! Schema::hasColumn('proveedores', 'persona_contacto')) {
                $table->string('persona_contacto', 150)->nullable()->after('numero_cuenta');
            }
        });

        if (Schema::hasTable('catalogo_formas_pago') && Schema::hasColumn('proveedores', 'catalogo_forma_pago_id')) {
            try {
                Schema::table('proveedores', function (Blueprint $table) {
                    $table->foreign('catalogo_forma_pago_id')
                        ->references('id')
                        ->on('catalogo_formas_pago')
                        ->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // La FK ya existía (re-ejecución parcial / entorno ya migrado)
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proveedores', function (Blueprint $table) {
            if (Schema::hasColumn('proveedores', 'catalogo_forma_pago_id')) {
                try {
                    $table->dropForeign(['catalogo_forma_pago_id']);
                } catch (\Throwable $e) {
                    // ignorar si no existía la FK
                }
            }
        });

        Schema::table('proveedores', function (Blueprint $table) {
            foreach (['persona_contacto', 'numero_cuenta', 'catalogo_forma_pago_id', 'nombre_comercial'] as $col) {
                if (Schema::hasColumn('proveedores', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::dropIfExists('catalogo_formas_pago');
    }
};
