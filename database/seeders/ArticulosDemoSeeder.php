<?php

namespace Database\Seeders;

use App\Models\Iva;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ArticulosDemoSeeder extends Seeder
{
    /**
     * Diez artículos de compra (tabla servicio, venta = 0) por usuario admin (1) y empresa (2).
     */
    public function run(): void
    {
        $ivaId = Iva::query()->orderBy('id')->value('id');

        $targets = User::query()
            ->whereIn('role', [1, 2])
            ->orderBy('id')
            ->pluck('id');

        if ($targets->isEmpty()) {
            $fallback = User::query()->orderBy('id')->value('id');
            if ($fallback === null) {
                $this->command?->warn('ArticulosDemoSeeder: no hay usuarios. Ejecute UserSeeder primero.');

                return;
            }
            $targets = collect([$fallback]);
        }

        $hoy = Carbon::now()->toDateString();

        $articulos = [
            ['nro' => 1, 'descripcion' => 'Aceite de oliva virgen extra 5L', 'precio' => 42.50, 'iva_percent' => 10],
            ['nro' => 2, 'descripcion' => 'Harina de trigo 25 kg', 'precio' => 18.90, 'iva_percent' => 4],
            ['nro' => 3, 'descripcion' => 'Azúcar blanquilla 1 kg (pack 10)', 'precio' => 12.00, 'iva_percent' => 4],
            ['nro' => 4, 'descripcion' => 'Sal marina fina 1 kg', 'precio' => 0.85, 'iva_percent' => 4],
            ['nro' => 5, 'descripcion' => 'Tomate triturado brik 3x400g', 'precio' => 2.45, 'iva_percent' => 4],
            ['nro' => 6, 'descripcion' => 'Pasta espaguetis 5 kg', 'precio' => 8.75, 'iva_percent' => 4],
            ['nro' => 7, 'descripcion' => 'Arroz redondo sack 20 kg', 'precio' => 24.00, 'iva_percent' => 4],
            ['nro' => 8, 'descripcion' => 'Leche entera UHT pack 6x1L', 'precio' => 5.60, 'iva_percent' => 4],
            ['nro' => 9, 'descripcion' => 'Café en grano 1 kg', 'precio' => 15.30, 'iva_percent' => 10],
            ['nro' => 10, 'descripcion' => 'Papel higiénico industrial (pack 12)', 'precio' => 9.99, 'iva_percent' => 21],
        ];

        foreach ($targets as $userId) {
            foreach ($articulos as $row) {
                Servicio::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'nro' => $row['nro'],
                        'venta' => 0,
                    ],
                    [
                        'descripcion' => $row['descripcion'],
                        'precio' => $row['precio'],
                        'iva_percent' => $row['iva_percent'],
                        'iva_id' => $ivaId,
                        'id_cuenta_contable' => null,
                        'created_at' => $hoy,
                        'updated_at' => $hoy,
                    ]
                );
            }
        }
    }
}
