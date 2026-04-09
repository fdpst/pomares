<?php

namespace App\Services;

use App\Clases\PayloadElectronicInvoicing;
use App\Clases\PayloadElectronicInvoicingLineas;
use App\Enums\ElectronicInvoiceType;
use App\Models\Recibo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use DateTime;

class ElectronicInvoicingService
{
    private string $base_url = "";
    private ?int $recibo_id = null;
    public $invoice_body = null;

    public function __construct()
    {
        $this->base_url = env("VERIFACTI_API_URL");
    }

    public static function getInstance($body = null)
    {
        return new self();
    }

    public function getReciboToSend(int $recibo_id, ElectronicInvoiceType $type)
    {
        $this->recibo_id = $recibo_id;
        $this->invoice_body = $this->mapBodyToApiSchema($recibo_id, $type);
        return $this;
    }

    public function send()
    {
        return $this->makeAPICall($this->invoice_body);
    }

    private function mapBodyToApiSchema(int $recibo_id, ElectronicInvoiceType $type)
    {
        $recibo = Recibo::with(["serie", "nro_factura", "servicios", "cliente"])
            ->where('id', $recibo_id)
            ->first();
        $lineas = $recibo->servicios->map(function ($item) {
            return new PayloadElectronicInvoicingLineas(
                $item->importe,
                $item->iva_percent,
                $item->importe_iva
            );
        });
        return new PayloadElectronicInvoicing(
            $recibo->serie->serie,
            $recibo->nro_factura->nro_factura ?? $recibo->nro_factura_rectificativa->nro_factura,
            (new DateTime($recibo->fecha))->format("d-m-Y"),
            $type,
            $recibo->observaciones ?? "N/A",
            $recibo->cliente->dni,
            $recibo->total,
            $recibo->cliente->nombre,
            $lineas ?? []
        );
    }

    /**
     * @param PayloadElectronicInvoicing $payload
     */
    private function makeAPICall(PayloadElectronicInvoicing $payload)
    {
        $json_to_send = (
            $this->cleanNullFields($this->invoice_body)
        );
        Log::debug("json a enviar " . json_encode($json_to_send));
        return Http::withOptions([
            'verify' => false
        ])->withHeaders([
            "Authorization" => "Bearer " . env("VERIFACTI_API_KEY")
        ])
            ->timeout(60)
            ->retry(1)
            ->post($this->base_url . "/verifactu/create", $json_to_send)
            ->json();
    }

    private function cleanNullFields($payload)
    {
        $payload = json_decode(json_encode($payload), true);
        foreach ($payload as $key => $value) {
            if ($value === null) {
                unset($payload[$key]);
            } elseif (is_array($value) || is_object($value)) {
                $payload[$key] = $this->cleanNullFields((array) $value);
            }
        }
        return $payload;
    }

    private function isEmpty($value)
    {
        return !$value || $value == '' || $value == null;
    }
}
