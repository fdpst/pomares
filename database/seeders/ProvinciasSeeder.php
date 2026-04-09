<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinciasSeeder extends Seeder
{
    public function run()  {
      DB::table('provincias')->insert(['nombre' => 'Almería']);
      DB::table('provincias')->insert(['nombre' => 'Cádiz']);
      DB::table('provincias')->insert(['nombre' => 'Córdoba']);
      DB::table('provincias')->insert(['nombre' => 'Granada']);
      DB::table('provincias')->insert(['nombre' => 'Huelva']);
      DB::table('provincias')->insert(['nombre' => 'Jaén']);
      DB::table('provincias')->insert(['nombre' => 'Málaga']);
      DB::table('provincias')->insert(['nombre' => 'Sevilla']);
      DB::table('provincias')->insert(['nombre' => 'Huesca']);
      DB::table('provincias')->insert(['nombre' => 'Teruel']);
      DB::table('provincias')->insert(['nombre' => 'Zaragoza']);
      DB::table('provincias')->insert(['nombre' => 'Asturias']);
      DB::table('provincias')->insert(['nombre' => 'Balears, Illes']);
      DB::table('provincias')->insert(['nombre' => 'Palmas, Las']);
      DB::table('provincias')->insert(['nombre' => 'Santa Cruz de Tenerife']);
      DB::table('provincias')->insert(['nombre' => 'Cantabria']);
      DB::table('provincias')->insert(['nombre' => 'Ávila']);
      DB::table('provincias')->insert(['nombre' => 'Burgos']);
      DB::table('provincias')->insert(['nombre' => 'León']);
      DB::table('provincias')->insert(['nombre' => 'Palencia']);
      DB::table('provincias')->insert(['nombre' => 'Salamanca']);
      DB::table('provincias')->insert(['nombre' => 'Segovia']);
      DB::table('provincias')->insert(['nombre' => 'Soria']);
      DB::table('provincias')->insert(['nombre' => 'Valladolid']);
      DB::table('provincias')->insert(['nombre' => 'Zamora']);
      DB::table('provincias')->insert(['nombre' => 'Albacete']);
      DB::table('provincias')->insert(['nombre' => 'Ciudad Real']);
      DB::table('provincias')->insert(['nombre' => 'Cuenca']);
      DB::table('provincias')->insert(['nombre' => 'Guadalajara']);
      DB::table('provincias')->insert(['nombre' => 'Toledo']);
      DB::table('provincias')->insert(['nombre' => 'Barcelona']);
      DB::table('provincias')->insert(['nombre' => 'Girona']);
      DB::table('provincias')->insert(['nombre' => 'Lleida']);
      DB::table('provincias')->insert(['nombre' => 'Tarragona']);
      DB::table('provincias')->insert(['nombre' => 'Alicante']);
      DB::table('provincias')->insert(['nombre' => 'Castellón']);
      DB::table('provincias')->insert(['nombre' => 'Valencia']);
      DB::table('provincias')->insert(['nombre' => 'Badajoz']);
      DB::table('provincias')->insert(['nombre' => 'Cáceres']);
      DB::table('provincias')->insert(['nombre' => 'Coruña, A']);
      DB::table('provincias')->insert(['nombre' => 'Lugo']);
      DB::table('provincias')->insert(['nombre' => 'Ourense']);
      DB::table('provincias')->insert(['nombre' => 'Pontevedra']);
      DB::table('provincias')->insert(['nombre' => 'Madrid']);
      DB::table('provincias')->insert(['nombre' => 'Murcia']);
      DB::table('provincias')->insert(['nombre' => 'Navarra']);
      DB::table('provincias')->insert(['nombre' => 'Araba/Álava']);
      DB::table('provincias')->insert(['nombre' => 'Bizkaia']);
      DB::table('provincias')->insert(['nombre' => 'Gipuzkoa']);
      DB::table('provincias')->insert(['nombre' => 'Rioja, La']);
      DB::table('provincias')->insert(['nombre' => 'Ceuta']);
      DB::table('provincias')->insert(['nombre' => 'Melilla']);
    }
}
