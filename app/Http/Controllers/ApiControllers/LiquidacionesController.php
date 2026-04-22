<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\CorrelativoCo;
use App\Helpers\GestorHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LiquidacionRequest;
use App\Models\FacturaRecibida;
use App\Models\FacturaRecibidaItems;
use App\Models\Liquidacion;
use App\Models\LiquidacionItem;
use App\Models\ProveedorComision;
use App\Services\ResumenLiquidacionPdfService;
use App\Traits\Files\HandlerFiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LiquidacionesController extends Controller
{
    public function index(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json([
                'status' => 200,
                'liquidaciones' => [],
            ]);
        }

        $liquidaciones = Liquidacion::orderBy('id', 'desc')
            ->with('proveedor')
            ->where('user_id', $effectiveUserId)
            ->get();

        return response()->json([
            'status' => 200,
            'liquidaciones' => $liquidaciones,
        ]);
    }

    public function store(LiquidacionRequest $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->merge(['user_id' => $effectiveUserId]);

        try {
            DB::beginTransaction();

            $nro = trim((string) ($request->nro_factura ?? ''));
            if ($nro === '' || $nro === 'null') {
                $nro = $this->generarSiguienteNroLiquidacion($effectiveUserId);
            }

            $liq = new Liquidacion;
            $liq->user_id = $effectiveUserId;
            $liq->proveedor_id = $request->proveedor_id;
            $liq->descripcion = $request->input('descripcion') ?? '';
            $liq->fecha = $request->fecha;
            $liq->retencion_id = ($request->retencion_id === 'null') ? null : $request->retencion_id;
            $liq->total = $request->total;
            $liq->nro_factura = $nro;
            $liq->save();

            $relativeDir = storage_path('app/public/recibos/userId_' . $effectiveUserId . '/liquidaciones/');
            if (!file_exists($relativeDir)) {
                mkdir($relativeDir, 0755, true);
            }

            $storeFiles = HandlerFiles::store($request, $relativeDir);
            $storeFiles->original['nombresArchivos'];

            if (count($storeFiles->original['nombresArchivos']) > 0) {
                $saved = Liquidacion::findOrFail($liq->id);
                $saved->imagen = $storeFiles->original['nombresArchivos'];
                $saved->update();
            }

            $this->saveItems($request->servicios, $liq);

            DB::commit();

            return response()->json($liq, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $liq = Liquidacion::with(['items'])->find($id);

        return response()->json(['success' => $liq], 200);
    }

    public function update(Request $request, $id)
    {
        $liqU = Liquidacion::findOrFail($id);
        $fallbackUserId = $request->user_id ?? $liqU->user_id;
        $effectiveUserId = GestorHelper::getUserId($request, $fallbackUserId);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->merge(['user_id' => $effectiveUserId]);

        try {
            DB::beginTransaction();

            $liqU->user_id = $effectiveUserId;
            $liqU->proveedor_id = $request->proveedor_id;
            if ($request->has('descripcion')) {
                $d = $request->descripcion;
                if ($d === null || $d === 'null' || $d === 'undefined') {
                    // No sustituir por null por un envío defectuoso del cliente
                } else {
                    $liqU->descripcion = $d;
                }
            }
            $liqU->fecha = $request->fecha;
            $liqU->retencion_id = ($request->retencion_id === 'null') ? null : $request->retencion_id;
            $liqU->total = $request->total;
            $liqU->nro_factura = $request->nro_factura;
            $liqU->save();

            $relativeDir = storage_path('app/public/recibos/userId_' . $effectiveUserId . '/liquidaciones/');
            if (!file_exists($relativeDir)) {
                mkdir($relativeDir, 0755, true);
            }
            $storeFiles = HandlerFiles::store($request, $relativeDir);
            $storeFiles->original['nombresArchivos'];

            if (count($storeFiles->original['nombresArchivos']) > 0) {
                $saved = Liquidacion::findOrFail($liqU->id);
                $saved->imagen = $storeFiles->original['nombresArchivos'];
                $saved->update();
            }

            $this->saveItems($request->servicios, $liqU);

            DB::commit();

            $liqU = Liquidacion::find($id);

            return response()->json([
                'liq' => $liqU,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function saveItems($items, Liquidacion $liquidacion): void
    {
        $data = json_decode($items, true);
        if ($data === null) {
            return;
        }

        $ids = [];
        $total = 0;

        foreach ($data as $item) {
            $id = null;
            if (isset($item['id']) && !empty($item['id'])) {
                $id = $item['id'];
            }

            $total += $item['total'];
            $ids[] = LiquidacionItem::updateOrCreate(['id' => $id], [
                'liquidacion_id' => $liquidacion->id,
                'concepto' => $item['concepto'],
                'cantidad' => $item['cantidad'],
                'id_servicio' => $item['id_servicio'] ?? 0,
                'precio' => $item['precio'],
                'dcto' => $item['dcto'],
                'iva' => $item['iva'],
                'total' => $item['total'],
            ])->id;
        }

        $liquidacion->update(['total' => $total]);
        LiquidacionItem::where('liquidacion_id', $liquidacion->id)->whereNotIn('id', $ids)->delete();
    }

    public function destroy($id)
    {
        Liquidacion::find($id)?->delete();

        return response()->json([
            'message' => 'Delete Successfully',
        ]);
    }

    /**
     * Siguiente CO-N para liquidaciones (misma serie que autofacturas / facturas recibidas del usuario).
     */
    public function siguienteNumero(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['nro' => 'CO-1'], 200);
        }

        return response()->json([
            'nro' => $this->generarSiguienteNroLiquidacion($effectiveUserId),
        ], 200);
    }

    private function generarSiguienteNroLiquidacion(int $userId): string
    {
        return CorrelativoCo::siguiente($userId);
    }

    public function duplicarLiquidacion(Request $request)
    {
        try {
            $origen = Liquidacion::with(['items'])->find($request->id);
            if (!$origen) {
                return response()->json(['error' => 'Liquidación no encontrada'], 404);
            }

            $uidDup = (int) ($request->user_id ?? $origen->user_id);

            $duplicada = Liquidacion::create([
                'fecha' => $request->fecha ?? $origen->fecha,
                'nro_factura' => $this->generarSiguienteNroLiquidacion($uidDup),
                'user_id' => $uidDup,
                'proveedor_id' => $request->proveedor_id ?? $origen->proveedor_id,
                'retencion_id' => ($request->retencion_id === 'null') ? null : ($request->retencion_id ?? $origen->retencion_id),
                'descripcion' => $request->descripcion ?? $origen->descripcion,
                'imagen' => $request->imagen ?? $origen->imagen,
                'total' => $request->total ?? $origen->total,
            ]);

            $items = isset($request->servicios) ? json_decode($request->servicios, true) : null;
            if ($items === null) {
                $items = $origen->items;
            }

            foreach ($items as $item) {
                $arr = is_array($item) ? $item : $item->toArray();
                LiquidacionItem::create([
                    'liquidacion_id' => $duplicada->id,
                    'concepto' => $arr['concepto'],
                    'cantidad' => $arr['cantidad'],
                    'precio' => $arr['precio'],
                    'dcto' => $arr['dcto'],
                    'iva' => $arr['iva'],
                    'total' => $arr['total'],
                    'id_servicio' => $arr['id_servicio'] ?? 0,
                ]);
            }

            return response()->json(['success' => $duplicada], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Crea una factura recibida (autofactura) por cada punto de venta (proveedor):
     * agrupa las liquidaciones seleccionadas por proveedor_id y, en cada factura,
     * una línea por liquidación con comisión calculable.
     * Cada autofactura recibe el siguiente Nº correlativo CO-N (serie común con liquidaciones del usuario).
     */
    public function crearFacturaComisiones(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $validator = Validator::make($request->all(), [
            'liquidacion_ids' => 'required|array|min:1',
            'liquidacion_ids.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $ids = array_values(array_unique(array_map('intval', $request->liquidacion_ids)));

        $liquidaciones = Liquidacion::with(['items'])
            ->where('user_id', $effectiveUserId)
            ->whereIn('id', $ids)
            ->get()
            ->sortBy('id')
            ->values();

        if ($liquidaciones->count() !== count($ids)) {
            return response()->json(['error' => 'Alguna liquidación no existe o no pertenece a su cuenta.'], 404);
        }

        $omitidas = [];
        $facturasCreadas = [];

        try {
            DB::beginTransaction();

            $porProveedor = $liquidaciones->groupBy(function (Liquidacion $liq) {
                return $liq->proveedor_id !== null && $liq->proveedor_id !== ''
                    ? (int) $liq->proveedor_id
                    : 0;
            });

            foreach ($porProveedor as $proveedorId => $liqsGrupo) {
                if ($proveedorId === 0) {
                    foreach ($liqsGrupo as $liq) {
                        $omitidas[] = [
                            'liquidacion_id' => $liq->id,
                            'motivo' => 'Liquidación sin punto de venta (proveedor)',
                        ];
                    }

                    continue;
                }

                $lineas = [];
                foreach ($liqsGrupo as $liq) {
                    $desglose = $this->desgloseComisionLiquidacion($liq, $effectiveUserId);
                    if ($desglose === null) {
                        $omitidas[] = [
                            'liquidacion_id' => $liq->id,
                            'motivo' => 'Sin comisión calculable para esta liquidación',
                        ];

                        continue;
                    }
                    $etiqueta = $this->etiquetaNroLiquidacion($liq);
                    $lineas[] = [
                        'liquidacion' => $liq,
                        'desglose' => $desglose,
                        'etiqueta' => $etiqueta,
                    ];
                }

                if ($lineas === []) {
                    continue;
                }

                $totalFactura = 0.0;
                foreach ($lineas as $ln) {
                    $totalFactura += (float) $ln['desglose']['total'];
                }
                $totalFactura = round($totalFactura, 2);

                $fechaFactura = now()->format('Y-m-d');
                $codigoResumen = ResumenLiquidacionPdfService::siguienteCodigoResumen((int) $effectiveUserId, $fechaFactura);
                $conceptoItem = 'Comisiones liquidación: ' . $codigoResumen;
                $descripcionFactura = $conceptoItem;

                $nroFactura = CorrelativoCo::siguiente($effectiveUserId);

                $fr = FacturaRecibida::create([
                    'user_id' => $effectiveUserId,
                    'proveedor_id' => $proveedorId,
                    'fecha' => $fechaFactura,
                    'retencion_id' => null,
                    'descripcion' => $descripcionFactura,
                    'nro_factura' => $nroFactura,
                    'total' => $totalFactura,
                    'imagen' => null,
                    'liquidacion_resumen_codigo' => $codigoResumen,
                ]);

                $payloads = [];
                foreach ($lineas as $ln) {
                    $liq = $ln['liquidacion'];
                    $liq->loadMissing('items');
                    $cantArt = (float) $liq->items->sum(fn ($it) => (float) $it->cantidad);
                    $d = $ln['desglose'];
                    $ivaPct = (float) $d['iva'];
                    $bruto = round((float) $d['total'], 2);
                    $net = round($bruto / (1 + $ivaPct / 100), 4);
                    $precioCom = round((float) $d['precio'], 4);
                    $payloads[] = [
                        'cantidad_art' => $cantArt,
                        'precio_com' => $precioCom,
                        'net' => $net,
                        'bruto' => $bruto,
                        'iva' => $ivaPct,
                        'dcto' => (float) $d['dcto'],
                    ];
                }

                $preciosUnicos = collect($payloads)->pluck('precio_com')->unique()->values();
                if ($preciosUnicos->count() === 1) {
                    $cantTot = round(array_sum(array_column($payloads, 'cantidad_art')), 4);
                    $netTot = round(array_sum(array_column($payloads, 'net')), 2);
                    $brutoTot = round(array_sum(array_column($payloads, 'bruto')), 2);
                    $ivaUse = (float) $payloads[0]['iva'];
                    $dctoUse = (float) $payloads[0]['dcto'];
                    $precioUnit = $cantTot > 0.00001
                        ? round($netTot / $cantTot, 4)
                        : $payloads[0]['precio_com'];
                    FacturaRecibidaItems::create([
                        'factura_recibidas_id' => $fr->id,
                        'concepto' => $conceptoItem,
                        'cantidad' => $cantTot,
                        'id_servicio' => 0,
                        'precio' => $precioUnit,
                        'dcto' => $dctoUse,
                        'iva' => $ivaUse,
                        'total' => $brutoTot,
                    ]);
                } else {
                    foreach ($payloads as $row) {
                        FacturaRecibidaItems::create([
                            'factura_recibidas_id' => $fr->id,
                            'concepto' => $conceptoItem,
                            'cantidad' => $row['cantidad_art'],
                            'id_servicio' => 0,
                            'precio' => $row['precio_com'],
                            'dcto' => $row['dcto'],
                            'iva' => $row['iva'],
                            'total' => $row['bruto'],
                        ]);
                    }
                }

                $idOrder = collect($lineas)->map(fn ($ln) => (int) $ln['liquidacion']->id)->unique()->values()->all();
                $byId = Liquidacion::with(['items.servicio', 'proveedor'])
                    ->where('user_id', $effectiveUserId)
                    ->whereIn('id', $idOrder)
                    ->get()
                    ->keyBy('id');
                $liqsOrdered = collect($idOrder)->map(fn ($id) => $byId->get($id))->filter();

                try {
                    ResumenLiquidacionPdfService::generarYAdjuntar($fr, $liqsOrdered, (int) $effectiveUserId);
                } catch (\Throwable $e) {
                    Log::warning('resumen_liquidacion_pdf', [
                        'factura_recibida_id' => $fr->id,
                        'message' => $e->getMessage(),
                    ]);
                }

                $facturasCreadas[] = $fr->fresh(['items']);
            }

            if ($facturasCreadas === []) {
                DB::rollBack();

                return response()->json([
                    'error' => 'Ninguna liquidación con punto de venta tiene comisión calculable (productos con comisión en el punto de venta).',
                    'omitidas' => $omitidas,
                ], 422);
            }

            DB::commit();

            return response()->json([
                'facturas_recibidas' => $facturasCreadas,
                'omitidas' => $omitidas,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /** Texto para conceptos cuando falta nro en liquidación */
    private function etiquetaNroLiquidacion(Liquidacion $liq): string
    {
        $n = trim((string) ($liq->nro_factura ?? ''));
        if ($n === '' || $n === 'null') {
            return '#' . $liq->id;
        }

        return $n;
    }

    /**
     * Desglose de comisiones por liquidación: cantidad = suma de unidades con comisión;
     * precio = comisión neta unitaria media; IVA 21%; total línea = neto + cuota.
     */
    private function desgloseComisionLiquidacion(Liquidacion $liq, $userId): ?array
    {
        $comisiones = ProveedorComision::where('proveedor_id', $liq->proveedor_id)
            ->where('user_id', $userId)
            ->get()
            ->keyBy(fn ($c) => (int) $c->servicio_id);

        $sumaComision = 0.0;
        $sumaCantidad = 0.0;

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
                $sumaComision += $baseTrasDcto * ((float) $c->valor / 100);
            } else {
                $sumaComision += $cantidad * (float) $c->valor;
            }
            $sumaCantidad += $cantidad;
        }

        $sumaComision = round($sumaComision, 4);
        if ($sumaComision <= 0) {
            return null;
        }

        $cantidadLinea = $sumaCantidad > 0 ? round($sumaCantidad, 4) : 1.0;
        $precioUnitario = $sumaCantidad > 0
            ? round($sumaComision / $sumaCantidad, 4)
            : round($sumaComision, 4);

        $neto = round($sumaComision, 2);
        $ivaPct = 21;
        $cuotaIva = round($neto * $ivaPct / 100, 2);
        $totalLinea = round($neto + $cuotaIva, 2);

        return [
            'cantidad' => $cantidadLinea,
            'precio' => $precioUnitario,
            'dcto' => 0,
            'iva' => $ivaPct,
            'total' => $totalLinea,
        ];
    }

}
