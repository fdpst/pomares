<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DistribuidoresDemoSeeder extends Seeder
{
    /**
     * Cinco distribuidores de ejemplo por cada usuario administrador (1) y empresa (2).
     * La API lista proveedores filtrando por el usuario autenticado (user_id).
     */
    public function run(): void
    {
        $targets = User::query()
            ->whereIn('role', [1, 2])
            ->orderBy('id')
            ->pluck('id');

        if ($targets->isEmpty()) {
            $fallback = User::query()->orderBy('id')->value('id');
            if ($fallback === null) {
                $this->command?->warn('DistribuidoresDemoSeeder: no hay usuarios. Ejecute UserSeeder primero.');

                return;
            }
            $targets = collect([$fallback]);
        }

        // id_provincia 35 = Alicante (ProvinciasTableSeeder)
        $provinciaId = 35;

        $distribuidores = [
            [
                'nro_proveedor' => 1,
                'nombre' => 'Distribuciones Levante S.L.',
                'email' => 'contacto@levante-distribuciones.demo',
                'telefono' => '965123001',
                'cif' => 'B03001234',
                'direccion' => 'Av. de la Constitución 12',
                'cp' => '03001',
                'localidad' => 'Alicante',
            ],
            [
                'nro_proveedor' => 2,
                'nombre' => 'Suministros Costa Blanca',
                'email' => 'pedidos@costablanca-supply.demo',
                'telefono' => '965123002',
                'cif' => 'B04567890',
                'direccion' => 'Pol. Ind. Las Atalayas, Nave 4',
                'cp' => '03114',
                'localidad' => 'Alicante',
            ],
            [
                'nro_proveedor' => 3,
                'nombre' => 'Mayorista Mediterráneo',
                'email' => 'ventas@medmayorista.demo',
                'telefono' => '965123003',
                'cif' => 'B11223344',
                'direccion' => 'Calle Maestro Torralba 8',
                'cp' => '03540',
                'localidad' => 'El Campello',
            ],
            [
                'nro_proveedor' => 4,
                'nombre' => 'Logística Pomares',
                'email' => 'info@logisticapomares.demo',
                'telefono' => '965123004',
                'cif' => 'B55667788',
                'direccion' => 'Ctra. Nacional 332, km 125',
                'cp' => '03130',
                'localidad' => 'Santa Pola',
            ],
            [
                'nro_proveedor' => 5,
                'nombre' => 'Comercial Marina Alta',
                'email' => 'admin@marinaalta-com.demo',
                'telefono' => '965123005',
                'cif' => 'B99887766',
                'direccion' => 'Plaza Mayor 3, bajo',
                'cp' => '03700',
                'localidad' => 'Denia',
            ],
        ];

        foreach ($targets as $userId) {
            foreach ($distribuidores as $row) {
                Proveedor::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'cif' => $row['cif'],
                    ],
                    array_merge($row, [
                        'user_id' => $userId,
                        'id_provincia' => $provinciaId,
                        'id_cuenta_contable' => null,
                    ])
                );
            }
        }
    }
}
