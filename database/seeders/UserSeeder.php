<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'provincia_id' => 1,
                'name' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'empresa@empresa.com'],
            [
                'provincia_id' => 1,
                'name' => 'Empresa demo',
                'password' => bcrypt('empresa'),
                'role' => 2,
            ]
        );
    }
}
