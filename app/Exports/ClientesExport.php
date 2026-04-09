<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientesExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $clientes;

    public function __construct(Collection $clientes)
    {
        $this->clientes = $clientes;
    }

    public function collection(): Collection
    {
        return $this->clientes;
    }

    public function map($cliente): array
    {
        return [
            $cliente->nombre_comercial,
            $cliente->nombre,
            $cliente->dni,
            $cliente->email,
            $cliente->telefono,
            $cliente->direccion,
            $cliente->codigo_postal,
            optional($cliente->pais)->nombre,
            optional($cliente->provincia)->nombre,
            $cliente->localidad,
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre comercial',
            'Nombre fiscal',
            'CIF/DNI',
            'Email',
            'Teléfono',
            'Dirección',
            'Código postal',
            'País',
            'Provincia',
            'Localidad',
        ];
    }
}

