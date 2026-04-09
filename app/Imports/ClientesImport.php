<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\Pais;
use App\Models\Provincia;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientesImport implements ToCollection, WithHeadingRow
{
    protected int $userId;

    protected int $nextNroCliente;

    protected Collection $paises;

    protected Collection $provincias;

    protected array $summary = [
        'processed' => 0,
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'messages' => [],
    ];

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->nextNroCliente = (int) (Cliente::where('user_id', $userId)->max('nro_cliente') ?? 0) + 1;
        $this->paises = Pais::all()->map(fn ($pais) => [
            'id' => $pais->id,
            'nombre' => $pais->nombre,
            'normalized' => $this->normalizeName($pais->nombre),
        ]);
        $this->provincias = Provincia::all()->map(fn ($provincia) => [
            'id' => $provincia->id,
            'id_pais' => $provincia->id_pais,
            'nombre' => $provincia->nombre,
            'normalized' => $this->normalizeName($provincia->nombre),
        ]);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // considerando encabezados

            if ($this->rowIsEmpty($row)) {
                $this->summary['skipped']++;
                continue;
            }

            $this->summary['processed']++;

            $rowData = $row->toArray();

            $dni = $this->sanitizeValue($this->getValue($rowData, ['cif_dni', 'dni']));
            $nombreFiscal = $this->sanitizeValue($this->getValue($rowData, ['nombre_fiscal', 'nombre']));
            $nombreComercial = $this->sanitizeValue($this->getValue($rowData, ['nombre_comercial']));

            if (!$dni || !$nombreFiscal) {
                $this->summary['skipped']++;
                $this->summary['messages'][] = "Fila {$rowNumber}: faltan datos obligatorios (CIF/DNI o Nombre fiscal).";
                continue;
            }

            $paisNombre = $this->sanitizeValue($this->getValue($rowData, ['pais']));
            $provinciaNombre = $this->sanitizeValue($this->getValue($rowData, ['provincia']));

            $paisId = $this->findPaisId($paisNombre);
            $provinciaId = $this->findProvinciaId($provinciaNombre, $paisId);

            $clienteData = [
                'nombre' => $nombreFiscal,
                'nombre_comercial' => $nombreComercial ?: $nombreFiscal,
                'dni' => $dni,
                'email' => strtolower($this->sanitizeValue($this->getValue($rowData, ['email'])) ?? ''),
                'telefono' => $this->sanitizePhone($this->getValue($rowData, ['telefono'])),
                'direccion' => $this->sanitizeValue($this->getValue($rowData, ['direccion'])),
                'codigo_postal' => $this->sanitizeValue($this->getValue($rowData, ['codigo_postal', 'cp'])),
                'localidad' => $this->sanitizeValue($this->getValue($rowData, ['localidad', 'ciudad'])),
                'pais_id' => $paisId,
                'provincia_id' => $provinciaId,
                'user_id' => $this->userId,
                'activo' => true,
                'fecha_alta' => $this->parseFechaAlta($this->getValue($rowData, ['fecha_alta'])),
            ];

            $attributes = [
                'dni' => $dni,
                'user_id' => $this->userId,
            ];

            $existing = Cliente::where($attributes)->first();
            if (!$existing) {
                $clienteData['nro_cliente'] = $this->nextNroCliente++;
            }

            try {
                $cliente = Cliente::updateOrCreate($attributes, array_filter(
                    $clienteData,
                    fn ($value) => $value !== null && $value !== ''
                ));

                if ($cliente->wasRecentlyCreated) {
                    $this->summary['created']++;
                } else {
                    $this->summary['updated']++;
                }
            } catch (\Throwable $exception) {
                $this->summary['messages'][] = "Fila {$rowNumber}: error {$exception->getMessage()}";
            }
        }
    }

    public function getSummary(): array
    {
        return $this->summary;
    }

    protected function rowIsEmpty(Collection $row): bool
    {
        return $row->filter(fn ($value) => $value !== null && $value !== '')->isEmpty();
    }

    protected function getValue(array $row, $keys)
    {
        $keys = (array) $keys;
        foreach ($keys as $key) {
            foreach ($this->keyVariants($key) as $variant) {
                if (array_key_exists($variant, $row) && $row[$variant] !== null && $row[$variant] !== '') {
                    return $row[$variant];
                }
            }
        }

        return null;
    }

    protected function sanitizeValue($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    protected function sanitizePhone($value): ?string
    {
        $value = $this->sanitizeValue($value);
        if (!$value) {
            return null;
        }

        $digits = preg_replace('/[^0-9+\s]/', '', $value);
        return $digits ?: null;
    }

    protected function parseFechaAlta($value): string
    {
        if (!$value) {
            return Carbon::now()->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $th) {
            return Carbon::now()->format('Y-m-d');
        }
    }

    protected function normalizeName(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $value = Str::ascii(trim($value));
        $value = preg_replace('/\s+/', ' ', $value);
        return mb_strtolower($value);
    }

    protected function findPaisId(?string $nombre): ?int
    {
        $normalized = $this->normalizeName($nombre);
        if (!$normalized) {
            return null;
        }

        $match = $this->paises->first(fn ($pais) => $pais['normalized'] === $normalized);
        if ($match) {
            return $match['id'];
        }

        $contains = $this->paises->first(function ($pais) use ($normalized) {
            return str_contains($pais['normalized'], $normalized) || str_contains($normalized, $pais['normalized']);
        });

        if ($contains) {
            return $contains['id'];
        }

        $closest = $this->closestByLevenshtein($this->paises, $normalized);
        return $closest['id'] ?? null;
    }

    protected function findProvinciaId(?string $nombre, ?int $paisId): ?int
    {
        $normalized = $this->normalizeName($nombre);
        if (!$normalized) {
            return null;
        }

        $collection = $this->provincias;
        if ($paisId) {
            $collection = $collection->where('id_pais', $paisId);
        }

        $match = $collection->first(fn ($provincia) => $provincia['normalized'] === $normalized);
        if ($match) {
            return $match['id'];
        }

        $contains = $collection->first(function ($provincia) use ($normalized) {
            return str_contains($provincia['normalized'], $normalized) || str_contains($normalized, $provincia['normalized']);
        });

        if ($contains) {
            return $contains['id'];
        }

        $closest = $this->closestByLevenshtein($collection, $normalized);
        return $closest['id'] ?? null;
    }

    protected function closestByLevenshtein(Collection $items, string $target): ?array
    {
        $best = null;
        $bestDistance = 3; // Umbral de similitud

        foreach ($items as $item) {
            if (!$item['normalized']) {
                continue;
            }

            $distance = levenshtein($target, $item['normalized']);
            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $best = $item;
            }
        }

        return $best;
    }

    protected function keyVariants(string $key): array
    {
        $base = Str::ascii(mb_strtolower($key));
        $variants = [
            $key,
            Str::slug($key),
            Str::slug($key, '_'),
            Str::snake($key),
            str_replace('_', '-', $key),
            str_replace('-', '_', $key),
            preg_replace('/\s+/', '_', mb_strtolower($key)),
            preg_replace('/[^a-z0-9]/', '', $base),
        ];

        return array_values(array_unique(array_filter($variants)));
    }
}

