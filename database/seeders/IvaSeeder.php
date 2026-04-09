<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Iva;

class IvaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ivas = [21, 20, 10, 4, 0];
        foreach ($ivas as $iva) {
            Iva::create([
                'descripcion' => $iva,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
