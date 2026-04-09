<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormasPagoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('formas_pago')->delete();
        
        \DB::table('formas_pago')->insert(array (
            0 => 
            array (
                'id' => 1,
                'descripcion' => 'Efectivo',
                'created_at' => '2024-01-19',
                'updated_at' => '2024-01-19',
            ),
            1 => 
            array (
                'id' => 2,
                'descripcion' => 'Domiciliación',
                'created_at' => '2024-01-19',
                'updated_at' => '2024-01-19',
            ),
            2 => 
            array (
                'id' => 3,
                'descripcion' => 'Transferencia',
                'created_at' => '2024-01-19',
                'updated_at' => '2024-01-19',
            ),
        ));
        
        
    }
}