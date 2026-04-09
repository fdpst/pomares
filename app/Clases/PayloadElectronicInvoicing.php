<?php

namespace App\Clases;

use App\Enums\ElectronicInvoiceType;
use App\Clases\PayloadElectronicInvoicingLineas;

class PayloadElectronicInvoicing
{
    /**
     * @param PayloadElectronicInvoicingLineas[] $lineas
     */
    public function __construct(
        public string $serie,
        public string $numero,
        public string $fecha_expedicion,
        public ElectronicInvoiceType $tipo_factura,
        public string $descripcion,
        public string $nif, // nif del cliente
        public float $importe_total,
        public string $nombre, //nombre del cliente
        public mixed $lineas,
    ) {}
}
