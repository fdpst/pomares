<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\FacturaRecibidaItems;
use App\Models\Retencion;
use Illuminate\Http\Request;
use App\Models\FacturaRecibida;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Traits\Files\HandlerFiles;
use App\Http\Requests\FacturaRecibidaRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FacturasRecibidasExport;
use Illuminate\Support\Facades\DB;
use App\Helpers\CorrelativoCo;
use App\Helpers\GestorHelper;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FacturaRecibidasController extends Controller
{

    protected function pathServer()
    {
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public', $PATH);
        $res = $pathPublicOut[0];
        return $res;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json([
                'status' => 200,
                'facturaRecibidas' => []
            ]);
        }

        $facturaRecibidas = GestorHelper::applyUserIdScope(
            FacturaRecibida::orderBy('id', 'desc')->with('proveedor'),
            $request
        )->get();

        return response()->json([
            'status' => 200,
            'facturaRecibidas' => $facturaRecibidas
        ]);
    }

    /**
     * Siguiente Nº de autofactura en formato CO-N (serie común con liquidaciones del usuario).
     */
    public function siguienteNroCo(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['nro' => 'CO-1'], 200);
        }

        return response()->json([
            'nro' => CorrelativoCo::siguiente($effectiveUserId),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacturaRecibidaRequest $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->merge(['user_id' => $effectiveUserId]);

        try {
            DB::beginTransaction();

            $nroRaw = trim((string) ($request->nro_factura ?? ''));
            if ($nroRaw === '' || strcasecmp($nroRaw, 'null') === 0) {
                $nroRaw = CorrelativoCo::siguiente($effectiveUserId);
            }

            $factRec = new FacturaRecibida;
            $factRec->user_id = $effectiveUserId;
            $factRec->proveedor_id = $request->proveedor_id;
            $factRec->descripcion = $request->descripcion;
            $factRec->fecha = $request->fecha;
            $factRec->retencion_id = ($request->retencion_id === 'null') ? null : $request->retencion_id;
            $factRec->total = $request->total;
            $factRec->nro_factura = $nroRaw;
            $factRec->contabilizado = $request->has('contabilizado')
                ? $request->boolean('contabilizado')
                : false;
            $factRec->save();

            // Crear el directorio en el disco 'recibos' con permisos/visibilidad correctos
            $relativeDir = storage_path('app/public/recibos/userId_' . $effectiveUserId . '/');
            if (!file_exists($relativeDir)) {
                mkdir($relativeDir, 0755, true);
            }

            $storeFiles = HandlerFiles::store($request, $relativeDir);
            $storeFiles->original['nombresArchivos'];

            if (count($storeFiles->original['nombresArchivos']) > 0) {
                $fr = FacturaRecibida::findOrFail($factRec->id);
                $fr->imagen = $storeFiles->original['nombresArchivos'];
                $fr->update();
            }

            $this->saveFacturaItems($request->servicios, $factRec);

            DB::commit();
            return response()->json($factRec, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $fr = FacturaRecibida::with(['items'])->find($id);

        return response()->json(['success' => $fr], 200);
    }

    /**
     * Marca o desmarca contabilizado desde el listado (sin enviar todo el formulario).
     */
    public function setContabilizado(Request $request, $id)
    {
        $fr = FacturaRecibida::findOrFail($id);
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId || !\App\Helpers\GestorHelper::ownsUserIdRow($request, $fr->user_id)) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $fr->contabilizado = $request->boolean('contabilizado');
        $fr->save();

        return response()->json([
            'facturaRecibida' => $fr->fresh(['proveedor']),
        ], 200);
    }

    /**
     * PDF de una autofactura (factura recibida) para previsualizar / descargar.
     */
    public function pdf(Request $request, $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $factura = GestorHelper::applyUserIdScope(
            FacturaRecibida::with(['proveedor.provincia', 'items'])->where('id', $id),
            $request
        )->first();

        if (!$factura) {
            return response()->json(['error' => 'Autofactura no encontrada'], 404);
        }

        $user = User::query()->where('id', $effectiveUserId)->with('provincia')->first();
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        try {
            $pdf = PDF::loadView('pdf.factura_recibida_individual', [
                'factura' => $factura,
                'items' => $factura->items,
                'userLog' => $user,
            ])->setPaper('a4', 'portrait');

            $slug = preg_replace('/[^a-zA-Z0-9_-]+/', '_', (string) ($factura->nro_factura ?: $factura->id));
            $filename = 'autofactura_' . $slug . '.pdf';

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Throwable $e) {
            Log::error('facturas-recibidas-pdf', [
                'id' => $id,
                'user_id' => $effectiveUserId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Error al generar el PDF',
                'message' => config('app.debug') ? $e->getMessage() : 'Error interno',
            ], 500);
        }
    }

    /**
     * PDF de resumen de liquidación (artículos) asociado a la autofactura generada desde liquidaciones.
     */
    public function resumenLiquidacionPdf(Request $request, $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $factura = GestorHelper::applyUserIdScope(
            FacturaRecibida::query()->where('id', $id),
            $request
        )->first();

        if (!$factura) {
            return response()->json(['error' => 'Autofactura no encontrada'], 404);
        }

        $path = trim((string) ($factura->resumen_liquidacion ?? ''));
        if ($path === '') {
            return response()->json(['error' => 'No hay resumen de liquidación para esta autofactura'], 404);
        }

        if (! Storage::disk('recibos')->exists($path)) {
            return response()->json(['error' => 'El archivo de resumen no está disponible'], 404);
        }

        $bin = Storage::disk('recibos')->get($path);

        return response($bin, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="resumen_liquidacion_' . $factura->id . '.pdf"',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $frU = FacturaRecibida::findOrFail($id);
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId || !\App\Helpers\GestorHelper::ownsUserIdRow($request, $frU->user_id)) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->merge(['user_id' => $effectiveUserId]);

        try {
            DB::beginTransaction();

            $frU->user_id = $effectiveUserId;
            $frU->proveedor_id = $request->proveedor_id;
            $frU->descripcion = $request->descripcion;
            $frU->fecha = $request->fecha;
            $frU->retencion_id = ($request->retencion_id === 'null') ? null : $request->retencion_id;
            $frU->total = $request->total;
            $frU->nro_factura = $request->nro_factura;
            if ($request->has('contabilizado')) {
                $frU->contabilizado = $request->boolean('contabilizado');
            }
            $frU->save();

            $relativeDir  = storage_path('app/public/recibos/userId_' .  $effectiveUserId . '/');
            if (!file_exists($relativeDir)) {
                mkdir($relativeDir, 0755, true);
            }
            $storeFiles = HandlerFiles::store($request, $relativeDir);
            $storeFiles->original['nombresArchivos'];

            if (count($storeFiles->original['nombresArchivos']) > 0) {
                $fr = FacturaRecibida::findOrFail($frU->id);
                $fr->imagen = $storeFiles->original['nombresArchivos'];
                $fr->update();
            }

            $this->saveFacturaItems($request->servicios, $frU);

            DB::commit();

            $frU = FacturaRecibida::find($id);
            return response()->json([
                'fr' => $frU
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function saveFacturaItems($items, $factura)
    {
        //try {
        $data = json_decode($items, true);
        if ($data != null) {
            $ids = [];
            $total = 0;
            //dd($data);
            foreach ($data as $item) {
                $id = null;
                if (isset($item['id']) && !empty($item['id'])) {
                    $id = $item['id'];
                }

                $total += $item['total'];
                $ids[] = FacturaRecibidaItems::updateOrCreate(['id' => $id], [
                    'factura_recibidas_id' => $factura->id,
                    'concepto' => $item['concepto'],
                    'cantidad' => $item['cantidad'],
                    'id_servicio' => $item['id_servicio'] ?? 0,
                    'precio' => $item['precio'],
                    'dcto' => $item['dcto'],
                    'iva' => $item['iva'],
                    'total' => $item['total'],
                ])->id;
            }

            $factura->update(['total' => $total]);
            $facturas_not_in = FacturaRecibidaItems::where('factura_recibidas_id', $factura->id)->whereNotIn('id', $ids)->delete();

            return ['ids' => $ids, 'facturas_not_in' => $facturas_not_in, 'code' => 200];
        }

        return ['error' => $data, 'code' => 400];
        //} catch (\Exception $e) {
        return ['error' => $e->getMessage(), 'code' => 400];
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = FacturaRecibida::find($id);
        if ($row) {
            $resPath = trim((string) ($row->resumen_liquidacion ?? ''));
            if ($resPath !== '' && Storage::disk('recibos')->exists($resPath)) {
                Storage::disk('recibos')->delete($resPath);
            }
            $row->delete();
        }

        return response()->json([
            'message' => 'Delete Successfully',
        ]);
    }

    public function getRetencion()
    {
        try {
            $retencion = Retencion::all();
            // Agregar la opción "Sin retención" al principio de la colección de retenciones
            $retencion->prepend(['id' => null, 'descripcion' => 'Sin retención']);
            return response()->json(['success' => $retencion], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function printRecibidas(Request $request, $fecha_inicio, $fecha_fin)
    {
        $user = $request->user();
        $fecha = Carbon::now();
        $desde = Carbon::parse($fecha_inicio);
        $hasta = Carbon::parse($fecha_fin);
        $empresa = 'Autónomo Cristina Gil Mateo';
        $ejercicio = $desde->format('Y');
        $recibos = FacturaRecibidaItems::whereHas('Factura', function ($query) use ($desde, $hasta, $request) {
            $query->where('fecha', '>=', $desde->format('Y-m-d'))
                ->where('fecha', '<=', $hasta->format('Y-m-d'));
            GestorHelper::applyUserIdScope($query, $request);
        })
            ->join('factura_recibidas', 'factura_recibidas.id', '=', 'factura_recibidas_items.factura_recibidas_id')
            ->orderBy('factura_recibidas.fecha', 'ASC')
            ->orderBy('factura_recibidas_id', 'ASC')
            ->get();
        $data = [
            'fecha' => $fecha,
            'desde' => $desde,
            'hasta' => $hasta,
            'empresa' => $empresa,
            'ejercicio' => $ejercicio,
            'recibos' => $recibos,
        ];
        $nombre_archivo = 'facturas_recibidas' . Carbon::now()->valueof() . '_';

        if ($request->tipo == 2) {
            $nombre_archivo = 'userId_' . $user->id . '/' . $nombre_archivo . '.xlsx';

            Excel::store(new FacturasRecibidasExport($data), $nombre_archivo, 'recibos');
            return "storage/recibos/{$nombre_archivo}";
        } else {
            $nombre_archivo =  $nombre_archivo . '.pdf';
            $view = 'pdf.facturas_recibidas';
            $pdf = PDF::loadView($view, $data)->setPaper('a4', 'landscape');



            Storage::disk('recibos')->put('userId_' . $user->id . '/' . $nombre_archivo, $pdf->output());
            //Determinar archivos para gestor documental
            //$archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $user->id, $pdf->output());
            return "storage/recibos/userId_{$user->id}/{$nombre_archivo}";
        }
    }

    public function duplicarFactura(Request $request)
    {
        try {
            $effectiveUserId = GestorHelper::getUserId($request);
            if (!$effectiveUserId) {
                return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
            }

            $factura = FacturaRecibida::with(['items'])->find($request->id);
            if (!$factura || !\App\Helpers\GestorHelper::ownsUserIdRow($request, $factura->user_id)) {
                return response()->json(['error' => 'Autofactura no encontrada'], 404);
            }

            $factura_duplicada = FacturaRecibida::create([
                'fecha' => $request->fecha ?? $factura->fecha,
                'nro_factura' => $request->nro_factura ?? $factura->nro_factura,
                'user_id' => $effectiveUserId,
                'proveedor_id' => $request->proveedor_id ?? $factura->proveedor_id,
                'retencion_id' => ($request->retencion_id === 'null') ? null : ($request->retencion_id ?? $factura->retencion_id),
                'descripcion' => $request->descripcion ?? $factura->descripcion,
                'imagen' => $request->imagen ?? $factura->imagen,
                'resumen_liquidacion' => null,
                'liquidacion_resumen_codigo' => null,
                'total' => $request->total ?? $factura->total,
                'contabilizado' => false,
            ]);

            $items = isset($request->servicios) ? json_decode($request->servicios, true) : $factura->items;
            if (isset($items)) {
                foreach ($items as $item) {
                    FacturaRecibidaItems::create([
                        'factura_recibidas_id' => $factura_duplicada->id,
                        'concepto' => $item['concepto'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $item['precio'],
                        'dcto' => $item['dcto'],
                        'iva' => $item['iva'],
                        'total' => $item['total'],
                    ]);
                }
            }

            // $this->saveFacturaItems($request->servicios ?? $factura->items, $factura_duplicada);

            return response()->json(['success' => $factura_duplicada], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
