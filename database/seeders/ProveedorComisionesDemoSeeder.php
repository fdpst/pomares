<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use App\Models\ProveedorComision;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProveedorComisionesDemoSeeder extends Seeder
{
    /**
     * Comisiones de ejemplo: distintos usuarios cliente (role 1 y 2) y,
     * si solo hay uno, un segundo distribuidor del mismo cliente.
     */
    public function run(): void
    {
        $users = User::query()
            ->whereIn('role', [1, 2])
            ->orderBy('id')
            ->get();

        if ($users->isEmpty()) {
            $users = User::query()->orderBy('id')->limit(2)->get();
        }

        foreach ($users as $user) {
            $proveedor = Proveedor::where('user_id', $user->id)->orderBy('id')->first();
            $servicios = Servicio::where('user_id', $user->id)
                ->where('venta', 0)
                ->orderBy('id')
                ->limit(3)
                ->get();

            if (!$proveedor || $servicios->isEmpty()) {
                continue;
            }

            ProveedorComision::firstOrCreate(
                [
                    'proveedor_id' => $proveedor->id,
                    'servicio_id' => $servicios[0]->id,
                ],
                [
                    'tipo' => 'porcentaje',
                    'valor' => 5.5,
                    'user_id' => $user->id,
                ]
            );

            if (isset($servicios[1])) {
                ProveedorComision::firstOrCreate(
                    [
                        'proveedor_id' => $proveedor->id,
                        'servicio_id' => $servicios[1]->id,
                    ],
                    [
                        'tipo' => 'importe',
                        'valor' => 12.5,
                        'user_id' => $user->id,
                    ]
                );
            }
        }

        $first = User::query()->orderBy('id')->first();
        if ($first) {
            $prov2 = Proveedor::where('user_id', $first->id)->orderBy('id')->skip(1)->first();
            $serv = Servicio::where('user_id', $first->id)->where('venta', 0)->orderBy('id')->first();
            if ($prov2 && $serv) {
                ProveedorComision::firstOrCreate(
                    [
                        'proveedor_id' => $prov2->id,
                        'servicio_id' => $serv->id,
                    ],
                    [
                        'tipo' => 'porcentaje',
                        'valor' => 3,
                        'user_id' => $first->id,
                    ]
                );
            }
        }
    }
}
