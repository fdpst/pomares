<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaisesTableSeeder::class);
        $this->call(ProvinciasTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AnioFiscalSeeder::class);
        $this->call(ApuntePredefinidoTableSeeder::class);
        $this->call(CategoriaCuentaContableTableSeeder::class);
        $this->call(FormasPagoTableSeeder::class);
        $this->call(IvaSeeder::class);
       $this->call(GestorDocumentalTableSeeder::class);
       $this->call(DragTableSeeder::class);
       $this->call(IngresosSeed::class);
       $this->call(ProvinciasSeeder::class);
      
        
    }
}
