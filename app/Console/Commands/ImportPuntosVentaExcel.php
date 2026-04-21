<?php

namespace App\Console\Commands;

use App\Models\CatalogoFormaPago;
use App\Models\Iva;
use App\Models\Proveedor;
use App\Models\ProveedorComision;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;

class ImportPuntosVentaExcel extends Command
{
    protected $signature = 'import:puntos-venta-excel
        {--file= : Ruta absoluta al .xlsx (p. ej. C:/Users/.../DATOS PUNTOS DE VENTA.xlsx)}
        {--user= : ID de usuario (empresa) dueño de los puntos de venta; por defecto primer usuario con rol Cliente (2)}
        {--dry-run : Solo muestra qué haría, sin escribir en BD}';

    protected $description = 'Importa puntos de venta desde el Excel «DATOS PUNTOS DE VENTA» (razón social, CIF, dirección, forma de pago, comisiones por código de artículo).';

    public function handle(): int
    {
        $path = $this->option('file');
        if (! $path) {
            $this->error('Indique --file="ruta\\al\\archivo.xlsx"');

            return self::FAILURE;
        }
        $path = str_replace('\\', '/', $path);
        if (! is_readable($path)) {
            $this->error("No se puede leer el archivo: {$path}");

            return self::FAILURE;
        }

        $userId = $this->option('user');
        if ($userId === null || $userId === '') {
            $user = User::query()->where('role', 2)->orderBy('id')->first();
            if (! $user) {
                $this->error('No hay ningún usuario con rol Cliente (2). Use --user=ID.');

                return self::FAILURE;
            }
            $userId = (int) $user->id;
            $this->info("Usando user_id={$userId} ({$user->email}).");
        } else {
            $userId = (int) $userId;
            if (! User::query()->whereKey($userId)->exists()) {
                $this->error("Usuario {$userId} no existe.");

                return self::FAILURE;
            }
        }

        $import = new class implements ToCollection
        {
            public Collection $rows;

            public function collection(Collection $collection): void
            {
                $this->rows = $collection;
            }
        };

        Excel::import($import, $path);
        $rows = $import->rows;
        if ($rows->count() < 3) {
            $this->error('El Excel no tiene filas de datos (se esperan cabeceras en filas 1-2 y datos desde la 3).');

            return self::FAILURE;
        }

        $headerCodes = $this->parseCommissionColumns($rows->get(1));
        if ($headerCodes === []) {
            $this->warn('No se detectaron columnas de comisión (fila 2 del Excel). Se importarán solo los puntos de venta sin comisiones.');
        } else {
            $this->info('Códigos de artículo para comisiones: '.implode(', ', array_column($headerCodes, 'code')));
        }

        $ivaId = Iva::query()->orderBy('id')->value('id');
        $dry = (bool) $this->option('dry-run');

        $dataRows = $rows->slice(2)->values();
        $createdPv = 0;
        $updatedPv = 0;
        $comisiones = 0;
        $skipped = 0;

        $work = function () use ($dataRows, $headerCodes, $userId, $ivaId, $dry, &$createdPv, &$updatedPv, &$comisiones, &$skipped): void {
            $servicioIdsByCode = $this->ensureServiciosForCodes($userId, $headerCodes, $ivaId, $dry);

            $nextNro = (int) (Proveedor::query()->where('user_id', $userId)->max('nro_proveedor') ?? 0);

            foreach ($dataRows as $row) {
                $cells = $this->normalizeRow($row);
                $nombre = $this->str($cells[0] ?? null);
                if ($nombre === '') {
                    $skipped++;

                    continue;
                }

                $nombreComercial = $this->str($cells[1] ?? null) ?: null;
                $cif = $this->normalizeCif($cells[2] ?? null);
                $calle = $this->str($cells[3] ?? null);
                $numero = $this->str($cells[4] ?? null);
                $cp = $this->str($cells[5] ?? null);
                $poblacion = $this->str($cells[6] ?? null);
                $formaPago = $this->str($cells[7] ?? null);
                $numeroCuenta = $this->truncate($this->str($cells[8] ?? null), 50);
                $contacto = $this->truncate($this->str($cells[9] ?? null), 150);
                $telefono = $this->truncate($this->normalizePhone($cells[10] ?? null), 35);
                $email = $this->truncate($this->str($cells[11] ?? null), 50);

                $direccion = $this->truncate(trim($calle.($numero !== '' ? ' '.$numero : '')), 100);

                $catalogoFormaPagoId = null;
                if ($formaPago !== '') {
                    if ($dry) {
                        $catalogoFormaPagoId = 0;
                    } else {
                        $fp = CatalogoFormaPago::firstOrCreate(
                            ['user_id' => $userId, 'descripcion' => $formaPago],
                            ['user_id' => $userId, 'descripcion' => $formaPago]
                        );
                        $catalogoFormaPagoId = $fp->id;
                    }
                }

                $attrs = [
                    'nombre' => $this->truncate($nombre, 100),
                    'nombre_comercial' => $nombreComercial ? $this->truncate($nombreComercial, 150) : null,
                    'cif' => $this->truncate($cif ?: null, 20),
                    'direccion' => $direccion !== '' ? $direccion : null,
                    'cp' => $this->truncate($cp ?: null, 20),
                    'localidad' => $this->truncate($poblacion ?: null, 100),
                    'id_provincia' => null,
                    'email' => $email !== '' ? $email : null,
                    'telefono' => $telefono !== '' ? $telefono : null,
                    'persona_contacto' => $contacto !== '' ? $contacto : null,
                    'numero_cuenta' => $numeroCuenta !== '' ? $numeroCuenta : null,
                    'catalogo_forma_pago_id' => $catalogoFormaPagoId ?: null,
                ];

                if ($dry) {
                    $createdPv++;

                    continue;
                }

                $proveedor = null;
                if ($cif !== '') {
                    $proveedor = Proveedor::query()
                        ->where('user_id', $userId)
                        ->where('cif', $cif)
                        ->first();
                }
                if (! $proveedor) {
                    $proveedor = Proveedor::query()
                        ->where('user_id', $userId)
                        ->where('nombre', $attrs['nombre'])
                        ->first();
                }

                if ($proveedor) {
                    if (! $proveedor->nro_proveedor) {
                        $nextNro++;
                        $attrs['nro_proveedor'] = $nextNro;
                    }
                    $proveedor->update($attrs);
                    $updatedPv++;
                } else {
                    $nextNro++;
                    $attrs['user_id'] = $userId;
                    $attrs['nro_proveedor'] = $nextNro;
                    $proveedor = Proveedor::create($attrs);
                    $createdPv++;
                }

                ProveedorComision::query()->where('proveedor_id', $proveedor->id)->delete();

                foreach ($headerCodes as $meta) {
                    $code = $meta['code'];
                    $col = $meta['col'];
                    $raw = $cells[$col] ?? null;
                    if ($raw === null || $raw === '') {
                        continue;
                    }
                    $valorPct = $this->excelPercentToStored((float) $raw);
                    if ($valorPct === null || $valorPct <= 0) {
                        continue;
                    }
                    $sid = $servicioIdsByCode[$code] ?? null;
                    if (! $sid) {
                        continue;
                    }
                    ProveedorComision::create([
                        'proveedor_id' => $proveedor->id,
                        'servicio_id' => $sid,
                        'tipo' => 'porcentaje',
                        'valor' => $valorPct,
                        'user_id' => $userId,
                    ]);
                    $comisiones++;
                }
            }
        };

        if ($dry) {
            $work();
            $this->info("[dry-run] Filas de datos: {$dataRows->count()}, puntos (simulados): {$createdPv}, comisiones no aplicadas.");

            return self::SUCCESS;
        }

        DB::transaction($work);

        $this->info("Puntos de venta creados: {$createdPv}, actualizados: {$updatedPv}, líneas de comisión: {$comisiones}, filas vacías omitidas: {$skipped}.");

        return self::SUCCESS;
    }

    /**
     * @return list<array{col:int, code:string}>
     */
    private function parseCommissionColumns(Collection $row1): array
    {
        $cells = $this->normalizeRow($row1);
        $out = [];
        for ($i = 12; $i < count($cells); $i++) {
            $code = $this->str($cells[$i] ?? null);
            if ($code === '') {
                break;
            }
            $out[] = ['col' => $i, 'code' => $code];
        }

        return $out;
    }

    /**
     * @param  list<array{col:int, code:string}>  $headerCodes
     * @return array<string, int> code => servicio id
     */
    private function ensureServiciosForCodes(int $userId, array $headerCodes, ?int $ivaId, bool $dry): array
    {
        $map = [];
        foreach ($headerCodes as $meta) {
            $code = $meta['code'];
            if ($dry) {
                $map[$code] = 0;

                continue;
            }
            $existing = Servicio::query()
                ->where('user_id', $userId)
                ->where('venta', 0)
                ->where('descripcion', $code)
                ->orderBy('id')
                ->first();
            if ($existing) {
                $map[$code] = (int) $existing->id;

                continue;
            }
            $maxNro = (int) (Servicio::query()->where('user_id', $userId)->where('venta', 0)->max('nro') ?? 0);
            $nro = $maxNro + 1;
            $s = Servicio::create([
                'user_id' => $userId,
                'nro' => $nro,
                'venta' => 0,
                'descripcion' => $code,
                'precio' => 0,
                'iva_percent' => 0,
                'iva_id' => $ivaId,
                'id_cuenta_contable' => null,
                'created_at' => now()->toDateString(),
                'updated_at' => now()->toDateString(),
            ]);
            $map[$code] = (int) $s->id;
        }

        return $map;
    }

    private function normalizeRow(Collection $row): array
    {
        $a = $row->toArray();
        foreach ($a as $k => $v) {
            if ($v instanceof \Stringable) {
                $a[$k] = (string) $v;
            }
        }

        return array_values($a);
    }

    private function str(mixed $v): string
    {
        if ($v === null) {
            return '';
        }
        $s = trim((string) $v);
        $s = preg_replace('/^\x{FEFF}/u', '', $s) ?? $s;

        return trim($s);
    }

    private function normalizeCif(?string $v): string
    {
        $s = strtoupper($this->str($v));

        return preg_replace('/\s+/', '', $s) ?? $s;
    }

    private function normalizePhone(mixed $v): string
    {
        $s = $this->str($v);
        $digits = preg_replace('/\D+/', '', $s) ?? '';

        return $digits;
    }

    private function truncate(?string $s, int $max): string
    {
        if ($s === null) {
            return '';
        }

        return mb_substr($s, 0, $max);
    }

    /**
     * El Excel usa decimales tipo 0,7 = 70 %. La liquidación usa valor/100 sobre la base.
     */
    private function excelPercentToStored(float $raw): ?float
    {
        if ($raw <= 0) {
            return null;
        }
        if ($raw > 0 && $raw <= 1) {
            return round($raw * 100, 4);
        }

        return round($raw, 4);
    }
}
