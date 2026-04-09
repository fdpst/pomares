<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Recibo;
use App\Models\SystemParam;
use App\Enums\ParamSystemEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use App\Helpers\DocumentColumnsHelper;

/**
 * @description This class is used to get PDF for "Factura proforma"|"Facura Rectificativa"|"Factura"
 */
class InvoiceToPDF
{
    public const INVOICE_PROFORMA = 'Factura Proforma';
    public const INVOICE_RECTIFICATIVA = 'Factura Rectificativa';
    public const INVOICE = 'Factura';
    private string $document_type = '';
    private ?int $current_invoice_id = null;
    public const DOCS_PREFIX = [
        self::INVOICE_PROFORMA => "FACTURAPROFORMA",
        self::INVOICE => "FACTURA",
        self::INVOICE_RECTIFICATIVA => "FACTURARECTIFICATIVA"
    ];

    public static function getInstance(): InvoiceToPDF
    {
        return new self();
    }

    public function getPdfForTypeAndInvoice(int $invoice_id, string $type = "")
    {
        if ($type != self::INVOICE_PROFORMA && $type != self::INVOICE && $type != self::INVOICE_RECTIFICATIVA && $type != "") {
            throw new \Exception("Tipo de factura no admitido.");
        }
        $this->document_type = $type;
        $this->current_invoice_id = $invoice_id;
        $recibo = $this->getInvoiceData($invoice_id);
        return $this->getPDFWithBrowsershot(
            $this->mapInvoiceDataToPdf($recibo, $type),
            $recibo
        );
    }


    private function getPDFWithBrowsershot(array $invoice_data, ?Recibo $recibo = null)
    {
        // Obtener el recibo si no se pasó
        if (!$recibo && $this->current_invoice_id) {
            $recibo = $this->getInvoiceData($this->current_invoice_id);
        }

        if ($recibo) {
            $template = $this->resolveDefaultTemplate($recibo->user_id);
            $templateKey = \App\Helpers\AlbaranTemplateHelper::normalizeTemplate($template);
            $viewName = \App\Helpers\AlbaranTemplateHelper::getViewName($templateKey);
            $columns = DocumentColumnsHelper::getForBusiness($recibo->user_id);
            $columnsForType = DocumentColumnsHelper::filterByDocType(
                $columns,
                $this->document_type === self::INVOICE
                    ? 'factura'
                    : ($this->document_type === self::INVOICE_PROFORMA
                        ? 'facturaproforma'
                        : 'facturarectificativa'),
            );

            // Preparar userLog con logo_base64 (solo el configurado en system-params; sin fallback)
            $user = $recibo->user2;
            if ($user) {
                $logoPath = $user->companyLogoParam()?->value
                    ? 'public/' . $user->companyLogoParam()->value
                    : '';
                $user->logo_base64 = $logoPath ? $this->getBusinessLogoBase64($logoPath) : '';
            }

            // Si el template es classic, usar new-recibo con formato de recibo
            if ($templateKey === 'classic') {
                $html = view($viewName, [
                    'recibo' => $recibo,
                    'fecha' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d-m-Y'),
                    'userLog' => $user,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'tipo' => $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa'),
                    'documentColumns' => $columnsForType,
                    'logo_base64' => $user->logo_base64 ?? '',
                    'signature' => $this->getSignatureBase64($user),
                ])->render();
            } else {
                // Otros templates usan formato de datos directo
                $lineas = $recibo->servicios ?? [];
                // Fusionar metadata con los servicios para que los campos dinámicos estén disponibles
                $lineas = collect($lineas)->map(function ($servicio) {
                    if ($servicio->metadata && is_array($servicio->metadata)) {
                        // Fusionar metadata con el servicio
                        foreach ($servicio->metadata as $key => $value) {
                            $servicio->setAttribute($key, $value);
                        }
                    }
                    return $servicio;
                })->toArray();

                $tipo = $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa');
                $invoice_footer = $user->invoiceFooter()?->value ?? '';
                $html = view($viewName, [
                    'data' => $lineas,
                    'recibo' => $recibo, // Pasar recibo completo para acceder a total_descuento
                    'userLog' => $user,
                    'total' => $invoice_data['invoice_totals']['total'] ?? $recibo->total ?? 0,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'fecha_emision' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d/m/Y'),
                    'cliente' => $recibo->cliente,
                    'tipo' => $tipo,
                    'invoice_footer' => $invoice_footer,
                    'documentColumns' => $columnsForType,
                    'logo_base64' => $user->logo_base64 ?? '',
                    'signature' => $this->getSignatureBase64($user),
                ])->render();
            }
        } else {
            // Fallback al template original si no se puede obtener el recibo
            $html = view("pdf.new-factura-pdf", $invoice_data)->render();
        }

        return Browsershot::html($html)->pdf();
    }

    private function resolveDefaultTemplate(int $businessId): string
    {
        $value = SystemParam::where('business_id', $businessId)
            ->where('param', ParamSystemEnum::ALBARAN_TEMPLATE->value)
            ->value('value');

        return $value ?? 'classic';
    }

    public static function getDocumentNameByType($type = '', $document_number): string
    {
        if ($type != self::INVOICE && $type != self::INVOICE_PROFORMA && $type != self::INVOICE_RECTIFICATIVA) throw new \Exception("tipo de documento no admitido");
        return (
            Carbon::now()->valueof() . '_' . self::DOCS_PREFIX[$type] .  '_' . $document_number . '.pdf'
        );
    }

    private function generatePdf(array $invoice_data): string
    {
        // Obtener el recibo si no se pasó
        $recibo = null;
        if ($this->current_invoice_id) {
            $recibo = $this->getInvoiceData($this->current_invoice_id);
        }

        if ($recibo) {
            $template = $this->resolveDefaultTemplate($recibo->user_id);
            $templateKey = \App\Helpers\AlbaranTemplateHelper::normalizeTemplate($template);
            $viewName = \App\Helpers\AlbaranTemplateHelper::getViewName($templateKey);
            $columns = DocumentColumnsHelper::getForBusiness($recibo->user_id);
            $columnsForType = DocumentColumnsHelper::filterByDocType(
                $columns,
                $this->document_type === self::INVOICE
                    ? 'factura'
                    : ($this->document_type === self::INVOICE_PROFORMA
                        ? 'facturaproforma'
                        : 'facturarectificativa'),
            );

            // Preparar userLog con logo_base64 (solo el configurado en system-params; sin fallback)
            $user = $recibo->user2;
            if ($user) {
                $logoPath = $user->companyLogoParam()?->value
                    ? 'public/' . $user->companyLogoParam()->value
                    : '';
                $user->logo_base64 = $logoPath ? $this->getBusinessLogoBase64($logoPath) : '';
            }

            // Si el template es classic, usar new-recibo con formato de recibo
            if ($templateKey === 'classic') {
                // Fusionar metadata con los servicios para que los campos dinámicos estén disponibles
                if ($recibo->servicios) {
                    $recibo->servicios = collect($recibo->servicios)->map(function ($servicio) {
                        if ($servicio->metadata && is_array($servicio->metadata)) {
                            // Fusionar metadata con el servicio
                            foreach ($servicio->metadata as $key => $value) {
                                $servicio->$key = $value;
                            }
                        }
                        return $servicio;
                    });
                }

                $pdf = PDF::loadView($viewName, [
                    'recibo' => $recibo,
                    'fecha' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d-m-Y'),
                    'userLog' => $user,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'tipo' => $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa'),
                    'documentColumns' => $columnsForType,
                    'signature' => $this->getSignatureBase64($user),
                ]);
            } else {
                // Otros templates usan formato de datos directo
                $lineas = $recibo->servicios ?? [];
                // Fusionar metadata con los servicios para que los campos dinámicos estén disponibles
                $lineas = collect($lineas)->map(function ($servicio) {
                    if ($servicio->metadata && is_array($servicio->metadata)) {
                        // Fusionar metadata con el servicio
                        foreach ($servicio->metadata as $key => $value) {
                            $servicio->setAttribute($key, $value);
                        }
                    }
                    return $servicio;
                })->toArray();

                $tipo = $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa');
                $invoice_footer = $user->invoiceFooter()?->value ?? '';
                $pdf = PDF::loadView($viewName, [
                    'data' => $lineas,
                    'recibo' => $recibo, // Pasar recibo completo para acceder a total_descuento
                    'userLog' => $user,
                    'total' => $invoice_data['invoice_totals']['total'] ?? $recibo->total ?? 0,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'fecha_emision' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d/m/Y'),
                    'cliente' => $recibo->cliente,
                    'tipo' => $tipo,
                    'invoice_footer' => $invoice_footer,
                    'documentColumns' => $columnsForType,
                    'logo_base64' => $user->logo_base64 ?? '',
                    'signature' => $this->getSignatureBase64($user),
                ]);
            }
        } else {
            // Fallback al template original si no se puede obtener el recibo
            $pdf = PDF::loadView("pdf.new-factura-pdf", $invoice_data);
        }

        return $pdf->output();
    }
    /**
     * @uses to test the pdf output view
     */
    private function streamPDF(array $invoice_data)
    {
        // Obtener el recibo si no se pasó
        $recibo = null;
        if ($this->current_invoice_id) {
            $recibo = $this->getInvoiceData($this->current_invoice_id);
        }

        if ($recibo) {
            $template = $this->resolveDefaultTemplate($recibo->user_id);
            $templateKey = \App\Helpers\AlbaranTemplateHelper::normalizeTemplate($template);
            $viewName = \App\Helpers\AlbaranTemplateHelper::getViewName($templateKey);
            $columns = DocumentColumnsHelper::getForBusiness($recibo->user_id);
            $columnsForType = DocumentColumnsHelper::filterByDocType(
                $columns,
                $this->document_type === self::INVOICE
                    ? 'factura'
                    : ($this->document_type === self::INVOICE_PROFORMA
                        ? 'facturaproforma'
                        : 'facturarectificativa'),
            );

            // Preparar userLog con logo_base64 (solo el configurado en system-params; sin fallback)
            $user = $recibo->user2;
            if ($user) {
                $logoPath = $user->companyLogoParam()?->value
                    ? 'public/' . $user->companyLogoParam()->value
                    : '';
                $user->logo_base64 = $logoPath ? $this->getBusinessLogoBase64($logoPath) : '';
            }

            // Si el template es classic, usar new-recibo con formato de recibo
            if ($templateKey === 'classic') {
                // Fusionar metadata con los servicios para que los campos dinámicos estén disponibles
                if ($recibo->servicios) {
                    $recibo->servicios = collect($recibo->servicios)->map(function ($servicio) {
                        if ($servicio->metadata && is_array($servicio->metadata)) {
                            // Fusionar metadata con el servicio
                            foreach ($servicio->metadata as $key => $value) {
                                $servicio->$key = $value;
                            }
                        }
                        return $servicio;
                    });
                }

                return view($viewName, [
                    'recibo' => $recibo,
                    'fecha' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d-m-Y'),
                    'userLog' => $user,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'tipo' => $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa'),
                    'documentColumns' => $columnsForType,
                    'logo_base64' => $user->logo_base64 ?? '',
                    'signature' => $this->getSignatureBase64($user),
                ]);
            } else {
                // Otros templates usan formato de datos directo
                $lineas = $recibo->servicios ?? [];
                // Fusionar metadata con los servicios para que los campos dinámicos estén disponibles
                $lineas = collect($lineas)->map(function ($servicio) {
                    if ($servicio->metadata && is_array($servicio->metadata)) {
                        // Fusionar metadata con el servicio
                        foreach ($servicio->metadata as $key => $value) {
                            $servicio->setAttribute($key, $value);
                        }
                    }
                    return $servicio;
                })->toArray();

                $tipo = $this->document_type === self::INVOICE ? 'factura' : ($this->document_type === self::INVOICE_PROFORMA ? 'facturaproforma' : 'facturarectificativa');
                $invoice_footer = $user->invoiceFooter()?->value ?? '';
                return view($viewName, [
                    'data' => $lineas,
                    'recibo' => $recibo, // Pasar recibo completo para acceder a total_descuento
                    'userLog' => $user,
                    'total' => $invoice_data['invoice_totals']['total'] ?? $recibo->total ?? 0,
                    'nro_factura' => $invoice_data['invoice_header']['invoice_number'] ?? '',
                    'fecha_emision' => $invoice_data['invoice_header']['date'] ?? Carbon::parse($recibo->fecha)->format('d/m/Y'),
                    'cliente' => $recibo->cliente,
                    'tipo' => $tipo,
                    'invoice_footer' => $invoice_footer,
                    'documentColumns' => $columnsForType,
                    'logo_base64' => $user->logo_base64 ?? '',
                    'signature' => $this->getSignatureBase64($user),
                ]);
            }
        }

        // Fallback al template original si no se puede obtener el recibo
        return view("pdf.new-factura-pdf", $invoice_data);
    }

    private function getInvoiceData(int $invoice_id): Recibo
    {
        return Recibo::where("id", $invoice_id)->with([
            'cliente',
            'cliente.provincia',
            'servicios',
            'user2',
            'user2.provincia',
            'nro_factura',
            'nro_factura_rectificativa',
            'nro_factura_prof'
        ])->first();
    }

    private function mapInvoiceDataToPdf(Recibo $invoice): array
    {
        $user = $invoice->user2;
        $logoPath = $user->companyLogoParam()?->value
            ? 'public/' . $user->companyLogoParam()->value
            : '';
        return [
            "business" => [
                "logo" => $logoPath ? $this->getBusinessLogoBase64($logoPath) : '',
                "name" => $invoice->user2->name,
                "locality" => $invoice->user2->ciudad,
                "province" => $invoice->user2->provincia->nombre,
                "cif" => $invoice->user2->cif,
                "phone" => $invoice->user2->telefono,
                "email" => $invoice->user2->email_comercial ?? $invoice->user2->email,
                "postal_code" => $invoice->user2->postal_code ?? '',
                "address" => $invoice->user2->direccion,
                "has_electronic_billing" => $invoice->user2->has_electronic_billing
            ],
            "customer" => [
                "name" => $invoice->cliente->nombre,
                "address" => $invoice->cliente->direccion,
                "postal_code" => $invoice->cliente->codigo_postal,
                "locality" => $invoice->cliente->localidad,
                "province" => $invoice->cliente->provincia->nombre,
                "cif" => $invoice->cliente->dni,
                "phone" => $invoice->cliente->telefono,
                "email" => $invoice->cliente->email
            ],
            "details" => $this->getDetailsForPdf($invoice),
            "invoice_header" => [
                "date" => (new \DateTime($invoice->fecha))->format('d-m-Y'),
                "invoice_number" => $this->getDocNumber($invoice),
                "description" => $invoice->observaciones,
                "invoice_qr_img" => $invoice->qr_code_electronic_invoice,
                "invoice_qr_string" => $invoice->qr_code_electronic_invoicing_string
            ],
            "invoice_totals" => [
                "subtotal" => \App\Helpers\PriceHelper::format($invoice->sub_total),
                "iva" => \App\Helpers\PriceHelper::format($invoice->iva),
                "total" => \App\Helpers\PriceHelper::format($invoice->total)
            ],
            "invoice_type" => $this->document_type,
            "signature" => $this->getSignatureBase64($user),
        ];
    }

    private function getSignatureBase64($user): string
    {
        $path = $user->companySignatureParam()?->value;
        if (empty($path)) {
            return '';
        }
        return $this->getBusinessLogoBase64('public/' . $path);
    }

    private function getDocNumber(Recibo $invoice)
    {
        if (!is_null($invoice->nro_factura)) {
            $this->document_type = self::INVOICE;
            return $invoice->nro_factura?->nro_factura;
        } else if (!is_null($invoice->nro_factura_prof)) {
            $this->document_type = self::INVOICE_PROFORMA;
            return $invoice->nro_factura_prof->nro_factura_prof;
        } else if (!is_null($invoice->nro_factura_rectificativa)) {
            $this->document_type = self::INVOICE_RECTIFICATIVA;
            return $invoice->nro_factura_rectificativa->nro_factura;
        }
        throw new \Exception("No se encontro numero de factura para recibo id: " . $invoice->id);
    }

    private function getDetailsForPdf(Recibo $invoice)
    {
        $result = [];
        foreach ($invoice->servicios as $servicio) {
            $result[] = [
                "nro" => $servicio->id,
                "quantity" => $servicio->cantidad,
                "description" => $servicio->descripcion,
                "price" => \App\Helpers\PriceHelper::format((float) $servicio->precio),
                "subtotal" => \App\Helpers\PriceHelper::format((float) $servicio->importe)
            ];
        }
        return $result;
    }

    private function getBusinessLogoBase64(string $logo_url)
    {
        try {
            if ($logo_url != "" && $logo_url) {
                $logo_url =  storage_path("app/" . $logo_url);
            }

            if (empty($logo_url) || !file_exists($logo_url)) {
                return '';
            }

            $binaryData = file_get_contents($logo_url);
            if ($binaryData === false) {
                return ''; // Retornar string vacío si no se puede leer el archivo
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($binaryData);
            return 'data:' . $mimeType . ';base64,' . base64_encode($binaryData);
        } catch (\Exception $e) {
            Log::error('Error al procesar el logo: ' . $e->getMessage());
            return ''; // Retornar string vacío en caso de error
        }
    }
}
