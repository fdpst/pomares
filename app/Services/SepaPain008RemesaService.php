<?php

namespace App\Services;

use App\Models\FacturaRecibida;
use App\Models\Proveedor;
use App\Models\User;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class SepaPain008RemesaService
{
    private const NS = 'urn:iso:std:iso:20022:tech:xsd:pain.008.001.08';

    private const RMT_INF_MAX = 140;

    /**
     * @param  Collection<int, FacturaRecibida>  $facturas
     * @return array{xml: string, filename: string, warnings: array{empresa: array, proveedores: array, mensajes: array<int, string>}}
     */
    public function build(User $creditor, Collection $facturas): array
    {
        $this->validateFacturas($facturas);

        $warnings = $this->buildWarningsPayload($creditor, $facturas);

        $collectionDate = now()->format('Y-m-d');
        $createdAt = now()->utc()->format('Y-m-d\TH:i:s\Z');
        $msgId = 'PRE' . now()->format('YmdHis') . '00000';

        $groups = $facturas->groupBy('proveedor_id');
        $transactions = [];

        foreach ($groups as $proveedorId => $group) {
            $proveedor = $group->first()->proveedor;
            if (! $proveedor instanceof Proveedor) {
                $warnings['mensajes'][] = "Autofacturas sin punto de venta (proveedor #{$proveedorId}): no incluidas en el XML.";
                continue;
            }

            $amount = round((float) $group->sum(fn (FacturaRecibida $f) => $this->amountFromLiquidaciones($f, $warnings)), 2);

            if ($amount <= 0) {
                $warnings['mensajes'][] = 'Punto de venta ' . $this->proveedorLabel($proveedor) . ': importe 0 (revise liquidaciones asociadas); no incluido en el XML.';
                continue;
            }

            $rmtLines = $group->map(fn (FacturaRecibida $f) => $this->buildRemittanceInfo($f))->all();
            $rmtInf = $this->joinRemittanceInfo($rmtLines);

            $transactions[] = [
                'proveedor' => $proveedor,
                'amount' => $amount,
                'rmt_inf' => $rmtInf,
            ];
        }

        if ($transactions === []) {
            $warnings['mensajes'][] = 'Empresa ' . $this->empresaLabel($creditor) . ': ningún adeudo se ha podido incluir en la remesa.';
        } else {
            $incluidos = array_map(
                fn (array $tx) => $this->proveedorNombreFiscal($tx['proveedor']),
                $transactions
            );
            $warnings['puntos_venta_incluidos'] = array_values(array_filter($incluidos));
        }

        $nbOfTxs = count($transactions);
        $ctrlSum = round(array_sum(array_column($transactions, 'amount')), 2);

        $xml = $this->renderXml(
            $creditor,
            $collectionDate,
            $createdAt,
            $msgId,
            $nbOfTxs,
            $ctrlSum,
            $transactions
        );

        $filename = 'REMESA_' . now()->format('Ymd_His') . '.xml';

        return [
            'xml' => $xml,
            'filename' => $filename,
            'warnings' => $warnings,
        ];
    }

    /**
     * @param  Collection<int, FacturaRecibida>  $facturas
     * @return array{empresa: array{id: int, nombre: string, faltan: array<int, string>}, proveedores: array<int, array{id: int, nombre: string, faltan: array<int, string>}>, mensajes: array<int, string>}
     */
    private function buildWarningsPayload(User $creditor, Collection $facturas): array
    {
        $empresaNombre = $this->empresaLabel($creditor);
        $empresaFaltan = $this->collectCreditorMissingFields($creditor);
        $mensajes = [];

        if ($empresaFaltan !== []) {
            $mensajes[] = 'Empresa ' . $empresaNombre . ': falta ' . implode(', ', $empresaFaltan) . '.';
        }

        $proveedoresWarnings = [];
        foreach ($facturas->groupBy('proveedor_id') as $proveedorId => $group) {
            $proveedor = $group->first()->proveedor;
            if (! $proveedor instanceof Proveedor) {
                continue;
            }
            $faltan = $this->collectProveedorMissingFields($proveedor);
            if ($faltan === []) {
                continue;
            }
            $pvLabel = $this->proveedorLabel($proveedor);
            $proveedoresWarnings[] = [
                'id' => (int) $proveedor->id,
                'nombre' => $pvLabel,
                'faltan' => $faltan,
            ];
            $mensajes[] = 'Punto de venta ' . $pvLabel . ': falta ' . implode(', ', $faltan) . '.';
        }

        return [
            'empresa' => [
                'id' => (int) $creditor->id,
                'nombre' => $this->creditorLegalName($creditor) ?: trim((string) $creditor->name) ?: 'Sin nombre',
                'faltan' => $empresaFaltan,
            ],
            'proveedores' => $proveedoresWarnings,
            'mensajes' => $mensajes,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function collectCreditorMissingFields(User $creditor): array
    {
        $missing = [];

        $cif = trim((string) $creditor->cif);
        if ($cif === '' || strcasecmp($cif, 'null') === 0) {
            $missing[] = 'CIF';
        }

        if ($this->creditorLegalName($creditor) === '') {
            $missing[] = 'nombre fiscal o nombre';
        }

        $iban = $this->normalizeIban((string) $creditor->cuenta);
        if ($iban === '' || strlen($iban) < 15 || $iban === '00000000000000000000') {
            $missing[] = 'IBAN de cobro (cuenta)';
        }

        return $missing;
    }

    /**
     * @return array<int, string>
     */
    private function collectProveedorMissingFields(Proveedor $proveedor): array
    {
        $missing = [];

        $iban = $this->normalizeIban((string) $proveedor->numero_cuenta);
        if ($iban === '' || strlen($iban) < 15) {
            $missing[] = 'IBAN';
        }

        if (trim((string) ($proveedor->nombre_comercial ?: $proveedor->nombre)) === '') {
            $missing[] = 'nombre';
        }

        return $missing;
    }

    private function empresaLabel(User $creditor): string
    {
        $name = $this->creditorLegalName($creditor);
        if ($name === '') {
            $name = 'Sin nombre';
        }

        return $name . ' (id ' . $creditor->id . ')';
    }

    private function proveedorLabel(Proveedor $proveedor): string
    {
        $name = $this->proveedorNombreFiscal($proveedor);

        return ($name !== '' ? $name : 'Sin nombre') . ' (id ' . $proveedor->id . ')';
    }

    /** Mismo criterio que la columna «Punto de venta» en la lista de autofacturas. */
    private function proveedorNombreFiscal(Proveedor $proveedor): string
    {
        $nombre = trim((string) $proveedor->nombre);
        if ($nombre !== '' && strcasecmp($nombre, 'null') !== 0) {
            return $nombre;
        }

        return trim((string) $proveedor->nombre_comercial);
    }

    private function proveedorNombreComercial(Proveedor $proveedor): string
    {
        $comercial = trim((string) $proveedor->nombre_comercial);
        if ($comercial !== '' && strcasecmp($comercial, 'null') !== 0) {
            return $comercial;
        }

        return $this->proveedorNombreFiscal($proveedor);
    }

    /**
     * Empresa acreedora = usuario dueño de las autofacturas (no el contexto compartido del helper).
     *
     * @param  Collection<int, FacturaRecibida>  $facturas
     */
    public function resolveCreditorFromFacturas(Collection $facturas): User
    {
        $ownerIds = $facturas
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($ownerIds->count() === 0) {
            throw ValidationException::withMessages([
                'ids' => ['Las autofacturas seleccionadas no tienen empresa asociada.'],
            ]);
        }

        if ($ownerIds->count() > 1) {
            throw ValidationException::withMessages([
                'ids' => ['Seleccione autofacturas de una sola empresa para generar la remesa.'],
            ]);
        }

        $creditor = User::find($ownerIds->first());
        if (! $creditor) {
            throw ValidationException::withMessages([
                'creditor' => ['No se encontró la empresa propietaria de las autofacturas.'],
            ]);
        }

        return $creditor;
    }

    private function creditorLegalName(User $creditor): string
    {
        $fiscal = trim((string) $creditor->nombre_fiscal);
        if ($fiscal !== '' && strcasecmp($fiscal, 'null') !== 0) {
            return $fiscal;
        }

        return trim((string) $creditor->name);
    }

    /**
     * @param  Collection<int, FacturaRecibida>  $facturas
     */
    private function validateFacturas(Collection $facturas): void
    {
        if ($facturas->isEmpty()) {
            throw ValidationException::withMessages([
                'ids' => ['Seleccione al menos una autofactura.'],
            ]);
        }
    }

    /**
     * Importe de remesa = «Importe a liquidar» del resumen PDF (artículos − comisiones).
     *
     * @param  array{empresa: array, proveedores: array, mensajes: array<int, string>}  $warnings
     */
    private function amountFromLiquidaciones(FacturaRecibida $factura, array &$warnings): float
    {
        if (! $factura->relationLoaded('liquidaciones')) {
            $factura->load('liquidaciones');
        }

        $liquidaciones = $factura->liquidaciones;
        if ($liquidaciones->isEmpty()) {
            $nro = trim((string) $factura->nro_factura);
            if ($nro === '' || strcasecmp($nro, 'null') === 0) {
                $nro = (string) $factura->id;
            }
            $warnings['mensajes'][] = "Autofactura {$nro}: sin liquidaciones asociadas; importe 0 en la remesa.";

            return 0.0;
        }

        return ResumenLiquidacionPdfService::calcularImporteALiquidar($factura, $liquidaciones);
    }

    private function buildRemittanceInfo(FacturaRecibida $factura): string
    {
        $fecha = $factura->fecha
            ? Carbon::parse($factura->fecha)->format('d/m/Y')
            : now()->format('d/m/Y');

        $codigo = trim((string) $factura->liquidacion_resumen_codigo);
        if ($codigo !== '') {
            return "LIQUIDACION N  {$codigo} DEL {$fecha}.";
        }

        $nro = trim((string) $factura->nro_factura);
        if ($nro === '' || $nro === 'null') {
            $nro = (string) $factura->id;
        }

        return "FACTURA N  {$nro} DEL {$fecha}.";
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function joinRemittanceInfo(array $lines): string
    {
        $text = implode(' ', array_filter(array_map('trim', $lines)));
        if (mb_strlen($text) <= self::RMT_INF_MAX) {
            return $text;
        }

        return mb_substr($text, 0, self::RMT_INF_MAX);
    }

    /**
     * @param  array<int, array{proveedor: Proveedor, amount: float, rmt_inf: string}>  $transactions
     */
    private function renderXml(
        User $creditor,
        string $collectionDate,
        string $createdAt,
        string $msgId,
        int $nbOfTxs,
        float $ctrlSum,
        array $transactions
    ): string {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $doc = $dom->createElementNS(self::NS, 'Document');
        $dom->appendChild($doc);

        $initn = $dom->createElement('CstmrDrctDbtInitn');
        $doc->appendChild($initn);

        $grpHdr = $dom->createElement('GrpHdr');
        $initn->appendChild($grpHdr);
        $this->appendText($dom, $grpHdr, 'MsgId', $msgId);
        $this->appendText($dom, $grpHdr, 'CreDtTm', $createdAt);
        $this->appendText($dom, $grpHdr, 'NbOfTxs', (string) $nbOfTxs);
        $this->appendText($dom, $grpHdr, 'CtrlSum', $this->formatAmount($ctrlSum));

        $initgPty = $dom->createElement('InitgPty');
        $grpHdr->appendChild($initgPty);
        $initgName = $this->creditorLegalName($creditor) ?: trim((string) $creditor->name) ?: 'Empresa ' . $creditor->id;
        $this->appendText($dom, $initgPty, 'Nm', $this->truncate($initgName, 70));
        $this->appendSepaCreditorId($dom, $initgPty, $creditor, 'org');

        $pmtInf = $dom->createElement('PmtInf');
        $initn->appendChild($pmtInf);

        $cifNorm = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string) $creditor->cif) ?? '');
        $pmtInfId = trim(($cifNorm !== '' ? $cifNorm . ' ' : '') . $collectionDate . ' RCUR 1');
        $this->appendText($dom, $pmtInf, 'PmtInfId', $pmtInfId);
        $this->appendText($dom, $pmtInf, 'PmtMtd', 'DD');
        $this->appendText($dom, $pmtInf, 'NbOfTxs', (string) $nbOfTxs);
        $this->appendText($dom, $pmtInf, 'CtrlSum', $this->formatAmount($ctrlSum));

        $pmtTpInf = $dom->createElement('PmtTpInf');
        $pmtInf->appendChild($pmtTpInf);
        $svcLvl = $dom->createElement('SvcLvl');
        $pmtTpInf->appendChild($svcLvl);
        $this->appendText($dom, $svcLvl, 'Cd', 'SEPA');
        $lclInstrm = $dom->createElement('LclInstrm');
        $pmtTpInf->appendChild($lclInstrm);
        $this->appendText($dom, $lclInstrm, 'Cd', 'CORE');
        $this->appendText($dom, $pmtTpInf, 'SeqTp', 'RCUR');

        $this->appendText($dom, $pmtInf, 'ReqdColltnDt', $collectionDate);

        $cdtr = $dom->createElement('Cdtr');
        $pmtInf->appendChild($cdtr);
        $cdtrName = $this->creditorLegalName($creditor) ?: trim((string) $creditor->name) ?: 'Empresa ' . $creditor->id;
        $this->appendText($dom, $cdtr, 'Nm', $this->truncate($cdtrName, 70));
        $this->appendPostalAddress($dom, $cdtr, $creditor->direccion, $creditor->postal_code, $creditor->ciudad);

        $cdtrAcct = $dom->createElement('CdtrAcct');
        $pmtInf->appendChild($cdtrAcct);
        $cdtrAcctId = $dom->createElement('Id');
        $cdtrAcct->appendChild($cdtrAcctId);
        $this->appendText($dom, $cdtrAcctId, 'IBAN', $this->creditorIbanForXml($creditor));

        $bic = strtoupper(trim((string) $creditor->bic));
        $cdtrAgt = $dom->createElement('CdtrAgt');
        $pmtInf->appendChild($cdtrAgt);
        $finInstnId = $dom->createElement('FinInstnId');
        $cdtrAgt->appendChild($finInstnId);
        if ($bic !== '') {
            $this->appendText($dom, $finInstnId, 'BICFI', $bic);
        }

        $this->appendText($dom, $pmtInf, 'ChrgBr', 'SLEV');

        $cdtrSchmeId = $dom->createElement('CdtrSchmeId');
        $pmtInf->appendChild($cdtrSchmeId);
        $schmeId = $dom->createElement('Id');
        $cdtrSchmeId->appendChild($schmeId);
        $prvtId = $dom->createElement('PrvtId');
        $schmeId->appendChild($prvtId);
        $this->appendSepaCreditorSchemeOthr($dom, $prvtId, $creditor);

        foreach ($transactions as $tx) {
            $this->appendDirectDebitTransaction($dom, $pmtInf, $tx['proveedor'], $tx['amount'], $tx['rmt_inf']);
        }

        return $dom->saveXML() ?: '';
    }

    private function appendDirectDebitTransaction(
        DOMDocument $dom,
        \DOMElement $pmtInf,
        Proveedor $proveedor,
        float $amount,
        string $rmtInf
    ): void {
        $drctDbtTxInf = $dom->createElement('DrctDbtTxInf');
        $pmtInf->appendChild($drctDbtTxInf);

        $pmtId = $dom->createElement('PmtId');
        $drctDbtTxInf->appendChild($pmtId);
        $endToEnd = $this->truncate($this->proveedorNombreFiscal($proveedor), 35);
        $this->appendText($dom, $pmtId, 'EndToEndId', $endToEnd !== '' ? $endToEnd : 'PV' . $proveedor->id);

        $instdAmt = $dom->createElement('InstdAmt');
        $instdAmt->setAttribute('Ccy', 'EUR');
        $instdAmt->appendChild($dom->createTextNode($this->formatAmount($amount)));
        $drctDbtTxInf->appendChild($instdAmt);

        $drctDbtTx = $dom->createElement('DrctDbtTx');
        $drctDbtTxInf->appendChild($drctDbtTx);
        $mndtRltdInf = $dom->createElement('MndtRltdInf');
        $drctDbtTx->appendChild($mndtRltdInf);
        $mndtId = $this->truncate($this->proveedorNombreComercial($proveedor), 35);
        $this->appendText($dom, $mndtRltdInf, 'MndtId', $mndtId !== '' ? $mndtId : 'PV' . $proveedor->id);
        $sigDate = $proveedor->created_at
            ? Carbon::parse($proveedor->created_at)->format('Y-m-d')
            : now()->format('Y-m-d');
        $this->appendText($dom, $mndtRltdInf, 'DtOfSgntr', $sigDate);

        $dbtrAgt = $dom->createElement('DbtrAgt');
        $drctDbtTxInf->appendChild($dbtrAgt);
        $dbtrAgt->appendChild($dom->createElement('FinInstnId'));

        $dbtr = $dom->createElement('Dbtr');
        $drctDbtTxInf->appendChild($dbtr);
        $dbtrNombre = $this->proveedorNombreFiscal($proveedor);
        $this->appendText(
            $dom,
            $dbtr,
            'Nm',
            $this->truncate($dbtrNombre !== '' ? $dbtrNombre : ('Punto de venta ' . $proveedor->id), 70)
        );
        $localidad = $proveedor->localidad;
        if ($localidad === null || trim((string) $localidad) === '') {
            $localidad = $proveedor->relationLoaded('provincia') && $proveedor->provincia
                ? $proveedor->provincia->nombre
                : '';
        }
        $this->appendPostalAddress($dom, $dbtr, $proveedor->direccion, $proveedor->cp, $localidad);
        $this->appendDebtorId($dom, $dbtr, (string) $proveedor->cif);

        $dbtrAcct = $dom->createElement('DbtrAcct');
        $drctDbtTxInf->appendChild($dbtrAcct);
        $dbtrAcctId = $dom->createElement('Id');
        $dbtrAcct->appendChild($dbtrAcctId);
        $this->appendText($dom, $dbtrAcctId, 'IBAN', $this->proveedorIbanForXml($proveedor));

        $rmtInfEl = $dom->createElement('RmtInf');
        $drctDbtTxInf->appendChild($rmtInfEl);
        $this->appendText($dom, $rmtInfEl, 'Ustrd', $rmtInf);
    }

    private function appendSepaCreditorId(DOMDocument $dom, \DOMElement $parent, User $creditor, string $partyType = 'org'): void
    {
        $id = $dom->createElement('Id');
        $parent->appendChild($id);

        $party = $dom->createElement($partyType === 'prvt' ? 'PrvtId' : 'OrgId');
        $id->appendChild($party);
        $this->appendSepaCreditorSchemeOthr($dom, $party, $creditor);
    }

    private function appendSepaCreditorSchemeOthr(DOMDocument $dom, \DOMElement $partyParent, User $creditor): void
    {
        $othr = $dom->createElement('Othr');
        $partyParent->appendChild($othr);
        $this->appendText($dom, $othr, 'Id', $this->sepaCreditorIdentifier($creditor));
        $schmeNm = $dom->createElement('SchmeNm');
        $othr->appendChild($schmeNm);
        $this->appendText($dom, $schmeNm, 'Prtry', 'SEPA');
    }

    private function appendDebtorId(DOMDocument $dom, \DOMElement $dbtr, string $cif): void
    {
        $cif = strtoupper(preg_replace('/[^A-Z0-9]/', '', trim($cif)) ?? '');
        if ($cif === '') {
            return;
        }

        $id = $dom->createElement('Id');
        $dbtr->appendChild($id);

        if ($this->isPersonalTaxId($cif)) {
            $prvtId = $dom->createElement('PrvtId');
            $id->appendChild($prvtId);
            $othr = $dom->createElement('Othr');
            $prvtId->appendChild($othr);
            $this->appendText($dom, $othr, 'Id', $cif);
        } else {
            $orgId = $dom->createElement('OrgId');
            $id->appendChild($orgId);
            $othr = $dom->createElement('Othr');
            $orgId->appendChild($othr);
            $this->appendText($dom, $othr, 'Id', $cif);
        }
    }

    private function appendPostalAddress(
        DOMDocument $dom,
        \DOMElement $parent,
        ?string $line1,
        ?string $postalCode,
        ?string $city
    ): void {
        $pstlAdr = $dom->createElement('PstlAdr');
        $parent->appendChild($pstlAdr);
        $this->appendText($dom, $pstlAdr, 'Ctry', 'ES');

        $addr1 = trim((string) $line1);
        if ($addr1 !== '') {
            $this->appendText($dom, $pstlAdr, 'AdrLine', $this->truncate($addr1, 70));
        }

        $cp = trim((string) $postalCode);
        $cityStr = trim((string) $city);
        $line2 = trim($cp . ' ' . $cityStr);
        if ($line2 !== '') {
            $this->appendText($dom, $pstlAdr, 'AdrLine', $this->truncate($line2, 70));
        }
    }

    private function appendText(DOMDocument $dom, \DOMElement $parent, string $name, string $value): void
    {
        $el = $dom->createElement($name);
        $el->appendChild($dom->createTextNode($value));
        $parent->appendChild($el);
    }

    private function sepaCreditorIdentifier(User $creditor): string
    {
        $cif = strtoupper(preg_replace('/[^A-Z0-9]/', '', (string) $creditor->cif) ?? '');
        if ($cif === '' || strcasecmp($cif, 'NULL') === 0) {
            return 'ES06000PENDIENTE';
        }
        $suffix = substr($cif, 0, 9);

        return 'ES06000' . $suffix;
    }

    private function creditorIbanForXml(User $creditor): string
    {
        $iban = $this->normalizeIban((string) $creditor->cuenta);
        if ($iban === '' || strlen($iban) < 15 || $iban === '00000000000000000000') {
            return '';
        }

        return $iban;
    }

    private function proveedorIbanForXml(Proveedor $proveedor): string
    {
        $iban = $this->normalizeIban((string) $proveedor->numero_cuenta);
        if ($iban === '' || strlen($iban) < 15) {
            return '';
        }

        return $iban;
    }

    private function normalizeIban(string $iban): string
    {
        return strtoupper(preg_replace('/\s+/', '', $iban) ?? '');
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, '.', '');
    }

    private function truncate(string $value, int $max): string
    {
        if (mb_strlen($value) <= $max) {
            return $value;
        }

        return mb_substr($value, 0, $max);
    }

    private function isPersonalTaxId(string $cif): bool
    {
        return (bool) preg_match('/^\d{8}[A-Z]$/i', $cif);
    }
}
