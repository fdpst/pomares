<?php

namespace App\Services;

use App\Models\FacturaRecibida;
use App\Models\Liquidacion;
use App\Models\LiquidacionItem;
use App\Models\ProveedorComision;
use App\Models\ServicioPrecioCambio;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ResumenLiquidacionPdfService
{
    /**
     * Genera el PDF de resumen de artículos de las liquidaciones asociadas a la autofactura y guarda la ruta en disco recibos.
     *
     * @param  Collection<int, Liquidacion>  $liquidacionesOrdenadas
     */
    public static function generarYAdjuntar(FacturaRecibida $fr, Collection $liquidacionesOrdenadas, int $userId): void
    {
        $codigoResumen = trim((string) ($fr->liquidacion_resumen_codigo ?? ''));
        if ($codigoResumen === '') {
            $codigoResumen = self::siguienteCodigoResumen($userId, $fr->fecha);
            $fr->liquidacion_resumen_codigo = $codigoResumen;
        }

        $filas = [];
        $totalImporte = 0.0;

        foreach ($liquidacionesOrdenadas as $liq) {
            if (!$liq) {
                continue;
            }
            $liq->loadMissing(['items.servicio', 'proveedor']);

            foreach ($liq->items as $it) {
                $nombre = $it->servicio->descripcion ?? $it->concepto ?? 'Artículo';
                $pu = (float) $it->precio;
                $dcto = (float) $it->dcto;
                $effUnit = $pu * (1 - $dcto / 100);
                $mismoPrecio = self::precioLineaIgualCatalogo($it);

                $suffix = '';
                if ($mismoPrecio) {
                    if ($liq->fecha) {
                        $suffix = ' (Desde el ' . Carbon::parse($liq->fecha)->format('d/m/Y') . ')';
                    }
                } else {
                    $hasta = self::fechaHastaSiPrecioDistinto($it, $userId, $liq);
                    if ($hasta !== null) {
                        $suffix = ' (Hasta el ' . $hasta . ')';
                    }
                }

                $concepto = $nombre . $suffix;
                $cant = (float) $it->cantidad;
                $imp = (float) $it->total;
                $totalImporte += $imp;

                $filas[] = [
                    'tipo' => 'linea',
                    'cantidad' => self::fmtCantidad($cant),
                    'concepto' => $concepto,
                    'precio_unit' => self::fmtMoney($effUnit),
                    'importe' => self::fmtMoney($imp),
                ];
            }
        }

        $fr->loadMissing(['proveedor.provincia']);

        $deducciones = self::buildDeduccionesComisiones($liquidacionesOrdenadas, $fr);
        $totalComisionConIva = round((float) ($fr->total ?? 0), 2);
        $importeLiquidar = round($totalImporte - $totalComisionConIva, 2);
        $cabeceraDerecha = self::bloquePuntoVenta($fr->proveedor);

        $pdf = PDF::loadView('pdf.resumen_liquidacion_factura', [
            'factura' => $fr,
            'filas' => $filas,
            'total_importe' => self::fmtMoney($totalImporte),
            'codigo_resumen' => $codigoResumen,
            'fecha_documento' => self::fechaDocumentoParaResumen($fr),
            'punto_venta_lineas' => $cabeceraDerecha,
            'filas_deducciones' => $deducciones['filas'],
            'mostrar_deducciones' => $deducciones['mostrar'],
            'total_deducciones' => self::fmtMoney($totalComisionConIva),
            'importe_liquidar' => self::fmtMoney($importeLiquidar),
        ])->setPaper('a4', 'portrait');

        $relPath = 'userId_' . $userId . '/resumen_liquidacion_fr_' . $fr->id . '.pdf';
        Storage::disk('recibos')->put($relPath, $pdf->output());

        $fr->resumen_liquidacion = $relPath;
        $fr->save();
    }

    /**
     * Código MM/YY-N por usuario y mes-año de la fecha de la autofactura (N correlativo sin límite de dígitos).
     */
    public static function siguienteCodigoResumen(int $userId, $fechaFactura): string
    {
        $fecha = $fechaFactura ? Carbon::parse($fechaFactura) : Carbon::now();
        $periodo = $fecha->format('m/y');
        $patron = $periodo . '-%';

        $max = 0;
        $refs = FacturaRecibida::where('user_id', $userId)
            ->whereNotNull('liquidacion_resumen_codigo')
            ->where('liquidacion_resumen_codigo', 'like', $patron)
            ->pluck('liquidacion_resumen_codigo');

        $regex = '/^' . preg_quote($periodo, '/') . '-(\d+)$/';
        foreach ($refs as $ref) {
            if (preg_match($regex, (string) $ref, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $periodo . '-' . ($max + 1);
    }

    private static function precioLineaIgualCatalogo(LiquidacionItem $it): bool
    {
        $sid = (int) $it->id_servicio;
        if ($sid <= 0) {
            return true;
        }
        $servicio = $it->servicio;
        if (!$servicio) {
            return true;
        }

        $pu = (float) $it->precio;
        $dcto = (float) $it->dcto;
        $effLine = $pu * (1 - $dcto / 100);
        $cat = (float) ($servicio->precio ?? 0);
        $effCat = $cat * (1 - $dcto / 100);

        return abs($effLine - $effCat) < 0.009;
    }

    /**
     * Solo cuando el precio de línea difiere del catálogo: fecha d/m/Y para el texto «Hasta el …» (log o fecha liquidación).
     */
    private static function fechaHastaSiPrecioDistinto(LiquidacionItem $it, int $userId, ?Liquidacion $liq = null): ?string
    {
        $sid = (int) $it->id_servicio;
        if ($sid <= 0) {
            return $liq && $liq->fecha ? Carbon::parse($liq->fecha)->format('d/m/Y') : null;
        }

        $logs = ServicioPrecioCambio::where('servicio_id', $sid)
            ->where('user_id', $userId)
            ->orderBy('id')
            ->get();

        $pu = (float) $it->precio;

        foreach ($logs as $row) {
            if (abs((float) $row->precio_anterior - $pu) < 0.009) {
                return Carbon::parse($row->created_at)->startOfDay()->subDay()->format('d/m/Y');
            }
        }
        foreach ($logs as $row) {
            if (abs((float) $row->precio_nuevo - $pu) < 0.009) {
                return Carbon::parse($row->created_at)->startOfDay()->subDay()->format('d/m/Y');
            }
        }

        if ($liq && $liq->fecha) {
            return Carbon::parse($liq->fecha)->format('d/m/Y');
        }

        return null;
    }

    /**
     * @return array{filas: array<int, array<string, string>>, mostrar: bool, neto_comisiones: float}
     */
    private static function buildDeduccionesComisiones(Collection $liquidaciones, FacturaRecibida $fr): array
    {
        $groups = [];

        foreach ($liquidaciones as $liq) {
            if (!$liq || !$liq->proveedor_id) {
                continue;
            }
            $liq->loadMissing(['items.servicio']);

            $comisiones = ProveedorComision::where('proveedor_id', $liq->proveedor_id)
                ->where('user_id', (int) $liq->user_id)
                ->get()
                ->keyBy(fn ($c) => (int) $c->servicio_id);

            foreach ($liq->items as $line) {
                $sid = (int) $line->id_servicio;
                if ($sid <= 0) {
                    continue;
                }
                $c = $comisiones->get($sid);
                if (!$c) {
                    continue;
                }

                $cantidad = (float) $line->cantidad;
                $precio = (float) $line->precio;
                $dcto = (float) $line->dcto;
                $bruto = $cantidad * $precio;
                $baseTrasDcto = $bruto * (1 - $dcto / 100);
                if ($c->tipo === 'porcentaje') {
                    $comNet = $baseTrasDcto * ((float) $c->valor / 100);
                } else {
                    $comNet = $cantidad * (float) $c->valor;
                }

                if (! isset($groups[$sid])) {
                    $nom = $line->servicio->descripcion ?? $line->concepto ?? 'Artículo';
                    $groups[$sid] = [
                        'cantidad' => 0.0,
                        'neto' => 0.0,
                        'concepto' => $nom,
                    ];
                }
                $groups[$sid]['cantidad'] += $cantidad;
                $groups[$sid]['neto'] += $comNet;
            }
        }

        if ($groups === []) {
            return ['filas' => [], 'mostrar' => false, 'neto_comisiones' => 0.0];
        }

        $netoSum = 0.0;
        foreach ($groups as $g) {
            $netoSum += (float) $g['neto'];
        }
        $netoTotal = round($netoSum, 2);

        $totalComFr = round((float) ($fr->total ?? 0), 2);
        $ivaImporte = round(max(0.0, $totalComFr - $netoTotal), 2);

        $filas = [];
        ksort($groups);
        foreach ($groups as $g) {
            $cant = (float) $g['cantidad'];
            $net = round((float) $g['neto'], 2);
            $pu = $cant > 0.00001 ? round((float) $g['neto'] / $cant, 4) : 0.0;
            $filas[] = [
                'cantidad' => self::fmtCantidad($cant),
                'concepto' => (string) $g['concepto'],
                'precio' => self::fmtMoney($pu),
                'importe' => '−' . self::fmtMoney($net),
            ];
        }

        $filas[] = [
            'cantidad' => '',
            'concepto' => 'Impuestos s/. Comisiones: 21%',
            'precio' => '',
            'importe' => '−' . self::fmtMoney($ivaImporte),
        ];

        return [
            'filas' => $filas,
            'mostrar' => true,
            'neto_comisiones' => $netoTotal,
        ];
    }

    /** Datos fiscales del punto de venta (proveedor) en cabecera derecha del PDF. */
    private static function bloquePuntoVenta(?Proveedor $p): array
    {
        if (!$p) {
            return [];
        }

        $p->loadMissing('provincia');

        $lineas = [];
        $nombre = trim((string) ($p->nombre_comercial ?: $p->nombre ?: ''));
        if ($nombre !== '') {
            $lineas[] = mb_strtoupper($nombre, 'UTF-8');
        }
        $cif = trim((string) ($p->cif ?? ''));
        if ($cif !== '') {
            $lineas[] = 'CIF. ' . $cif;
        }
        $dir = trim((string) ($p->direccion ?? ''));
        if ($dir !== '') {
            $lineas[] = $dir;
        }
        $cp = trim((string) ($p->cp ?? ''));
        $loc = trim((string) ($p->localidad ?? ''));
        $prov = $p->provincia ? trim((string) $p->provincia->nombre) : '';
        $ultima = trim($cp . ' ' . $loc . ($prov !== '' ? '-' . $prov : ''));
        if ($ultima !== '') {
            $lineas[] = $ultima;
        }

        return $lineas;
    }

    /**
     * Regenera el PDF de resumen para una autofactura con liquidaciones vinculadas.
     */
    public static function regenerarParaFactura(FacturaRecibida $fr): void
    {
        if (! $fr->relationLoaded('liquidaciones')) {
            $fr->load('liquidaciones');
        }

        if ($fr->liquidaciones->isEmpty()) {
            throw new \RuntimeException('No hay liquidaciones asociadas a esta autofactura.');
        }

        $idOrder = $fr->liquidaciones
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $byId = Liquidacion::with(['items.servicio', 'proveedor'])
            ->whereIn('id', $idOrder)
            ->get()
            ->keyBy('id');

        $liqsOrdered = collect($idOrder)
            ->map(fn ($id) => $byId->get($id))
            ->filter()
            ->values();

        self::generarYAdjuntar($fr->fresh(), $liqsOrdered, (int) $fr->user_id);
    }

    public static function fechaDocumentoParaResumen(FacturaRecibida $fr): string
    {
        $raw = $fr->fecha_resumen_liquidacion ?? $fr->fecha;

        return $raw
            ? Carbon::parse($raw)->format('d/m/Y')
            : Carbon::now()->format('d/m/Y');
    }

    private static function fmtMoney(float $v): string
    {
        return number_format($v, 2, ',', '.');
    }

    private static function fmtCantidad(float $v): string
    {
        if (abs($v - round($v)) < 0.00001) {
            return (string) (int) round($v);
        }

        return rtrim(rtrim(number_format($v, 4, ',', '.'), '0'), ',');
    }
}
