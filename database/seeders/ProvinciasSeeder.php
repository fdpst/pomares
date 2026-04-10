<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Antes insertaba solo `nombre` en `provincias`, pero la tabla exige `id_pais`
 * (FK a `paises`), lo que provocaba SQLSTATE[HY000]: 1364 en MySQL strict.
 *
 * Los datos correctos están en {@see ProvinciasTableSeeder} (ya invocado desde DatabaseSeeder).
 */
class ProvinciasSeeder extends Seeder
{
    public function run(): void
    {
        // Sin acción: mantener la clase por si hay referencias antiguas; usar ProvinciasTableSeeder.
    }
}
