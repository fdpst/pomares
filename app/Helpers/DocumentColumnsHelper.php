<?php

namespace App\Helpers;

use App\Enums\ParamSystemEnum;
use App\Models\SystemParam;

class DocumentColumnsHelper
{
    /**
     * Configuración por defecto de columnas para documentos.
     */
    private const DEFAULT_COLUMNS = [
        [
            'id' => 'cantidad',
            'label' => 'Cantidad',
            'field' => 'cantidad',
            'align' => 'center',
            'width' => 12,
            'format' => 'number',
            'order' => 10,
            'docTypes' => [
                'factura',
                'facturarectificativa',
                'facturaproforma',
                'presupuesto',
                'nota',
                'parte-trabajo',
                'form',
            ],
            'enabled' => true,
        ],
        [
            'id' => 'descripcion',
            'label' => 'Descripción',
            'field' => 'descripcion',
            'align' => 'start',
            'width' => 40,
            'format' => 'text',
            'order' => 20,
            'docTypes' => [
                'factura',
                'facturarectificativa',
                'facturaproforma',
                'presupuesto',
                'nota',
                'parte-trabajo',
                'form',
            ],
            'enabled' => true,
        ],
        [
            'id' => 'servicio',
            'label' => 'Servicio/Artículo',
            'field' => 'id_servicio',
            'align' => 'start',
            'width' => 16,
            'format' => 'text',
            'order' => 25,
            'docTypes' => ['form'],
            'enabled' => true,
        ],
        [
            'id' => 'precio',
            'label' => 'Precio',
            'field' => 'precio',
            'align' => 'end',
            'width' => 12,
            'format' => 'currency',
            'order' => 30,
            'docTypes' => [
                'factura',
                'facturarectificativa',
                'facturaproforma',
                'presupuesto',
                'nota',
                'parte-trabajo',
                'form',
            ],
            'enabled' => true,
        ],
        [
            'id' => 'iva',
            'label' => 'IVA (%)',
            'field' => 'iva_percent',
            'align' => 'center',
            'width' => 12,
            'format' => 'percent',
            'order' => 40,
            'docTypes' => [
                'factura',
                'facturarectificativa',
                'facturaproforma',
                'form',
            ],
            'enabled' => true,
        ],
        [
            'id' => 'importe',
            'label' => 'Importe',
            'field' => 'importe',
            'align' => 'end',
            'width' => 12,
            'format' => 'currency',
            'order' => 50,
            'docTypes' => [
                'factura',
                'facturarectificativa',
                'facturaproforma',
                'presupuesto',
                'nota',
                'parte-trabajo',
                'form',
            ],
            'enabled' => true,
        ],
    ];

    public static function defaults(): array
    {
        return self::DEFAULT_COLUMNS;
    }

    public static function getForBusiness(int $businessId): array
    {
        try {
            $param = SystemParam::where('business_id', $businessId)
                ->where('param', ParamSystemEnum::DOCUMENT_COLUMNS->value)
                ->first();

            if (!$param || empty($param->value)) {
                return self::DEFAULT_COLUMNS;
            }

            $decoded = json_decode($param->value, true);
            return self::normalize($decoded);
        } catch (\Throwable $th) {
            return self::DEFAULT_COLUMNS;
        }
    }

    public static function filterByDocType(array $columns, string $docType): array
    {
        $normalizedType = strtolower(trim($docType));
        $normalized = self::normalize($columns);

        return array_values(array_filter($normalized, function ($column) use ($normalizedType) {
            $docTypes = $column['docTypes'] ?? [];
            if (!is_array($docTypes) || !$column['enabled']) {
                return false;
            }

            $docTypes = array_map(fn ($type) => strtolower(trim($type)), $docTypes);
            return in_array($normalizedType, $docTypes);
        }));
    }

    public static function normalize($columns): array
    {
        if (!is_array($columns)) {
            return self::DEFAULT_COLUMNS;
        }

        $normalized = [];
        foreach ($columns as $index => $column) {
            if (!is_array($column)) {
                continue;
            }

            $docTypes = [];
            if (isset($column['docTypes']) && is_array($column['docTypes'])) {
                $docTypes = array_values(array_filter(
                    array_map(fn ($type) => strtolower(trim($type)), $column['docTypes'])
                ));
            }

            $normalized[] = [
                'id' => $column['id'] ?? 'col-' . $index,
                'label' => $column['label'] ?? ($column['field'] ?? 'Columna ' . ($index + 1)),
                'field' => $column['field'] ?? '',
                'align' => $column['align'] ?? 'start',
                'width' => $column['width'] ?? null,
                'format' => $column['format'] ?? 'text',
                'order' => $column['order'] ?? $index,
                'docTypes' => $docTypes ?: ['form'],
                'enabled' => isset($column['enabled']) ? (bool)$column['enabled'] : true,
            ];
        }

        return count($normalized) ? $normalized : self::DEFAULT_COLUMNS;
    }
}

