<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApuntePredefinidoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('apunte_predefinido')->delete();
        
        \DB::table('apunte_predefinido')->insert(array (
            0 => 
            array (
                'id' => 1,
                'descripcion' => 'Asiento predefinido',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'descripcion' => 'Factura de venta 21%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'descripcion' => 'Factura de venta 10%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'descripcion' => 'Factura de venta 4%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'descripcion' => 'Factura de venta con inversión del sujeto pasivo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
            'descripcion' => 'Factura de venta intrecomunitaria (bienes)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
            'descripcion' => 'Factura de venta intracomunitaria (servicios)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'descripcion' => 'Factura de venta con retención del 7%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'descripcion' => 'Factura de venta con retención del 15%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'descripcion' => 'Factura de venta con retención del 19%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'descripcion' => 'Factura de compra 21%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'descripcion' => 'Factura de compra 10%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'descripcion' => 'Factura de compra 4%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
            'descripcion' => 'Factura de compra intracomunitaria (bienes)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
            'descripcion' => 'Factura de compra intracomunitaria (servicios)',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'descripcion' => 'factura de compra por inversión de sujeto pasivo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'descripcion' => 'Factura de compra por retención de profesional 7%',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'descripcion' => 'Factura de compra por retención de profesional',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'descripcion' => 'Factura de compra con retenion de alquiler',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}