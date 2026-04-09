<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GestorDocumental;

class GestorDocumentalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gD = new  GestorDocumental;
        $gD->user_id = 1;
        $gD->name = 'Documentación General';
        $gD->children = json_encode([]);
        $gD->save();

        $gD = new  GestorDocumental;
        $gD->user_id = 1;
        $gD->name = 'Facturas Emitidas';
        $gD->children = json_encode([]);
        $gD->save();

        $gD = new  GestorDocumental;
        $gD->user_id = 1;
        $gD->name = 'Facturas Recibidas';
        $gD->children = json_encode([]);
        $gD->save();
    }
}
