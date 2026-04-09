<?php

namespace App\Clases;

class PayloadElectronicInvoicingLineas
{
    /**
     * @param float $base_imponible required
     */
    public function __construct(
        public float $base_imponible,
        public $tipo_impositivo = null,
        public $cuota_repercutida = null,
        public $impuesto = null,
        public $calificacion_operacion = null,
        public $clave_regimen = null,
        public $operacion_exenta = null,
        public $base_imponible_a_coste = null,
        public $tipo_recargo_equivalencia = null,
        public $cuota_recargo_equivalencia = null
    ) {}
}
