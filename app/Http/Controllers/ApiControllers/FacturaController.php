<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Resources\FacturaResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\NroFactura;
use App\Models\NroFacturaProforma;
use App\Models\Recibo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Albaranes\AlbaranesEnviado;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Response;
use App\Exports\FacturasExport;
use App\Exports\FacturasExportLegacy;
use App\Exports\FacturasEmitidasExport;
use App\Exports\FacturasExportRecibidas;
use App\Mail\FacturaLote;
use App\Models\FacturaRecibida;
use App\Models\MailPredefinido;
use Illuminate\Support\Facades\Mail;
use App\Helpers\GestorHelper;
use App\Helpers\ParseHelper;

class FacturaController extends Controller
{
    protected ParseHelper $parseHelper;

    public function __construct(ParseHelper $parseHelper)
    {
        $this->parseHelper = $parseHelper;
    }
    /**
     * Comprueba si el usuario actual puede eliminar una factura de la empresa (user_id del recibo).
     * - Propia factura (user_id coincide con el usuario).
     * - Admin (role 1) puede eliminar cualquiera.
     * - Empresa/cliente (role 2) puede eliminar facturas de su empresa (user_id == su id).
     * - Gestor o empleado (role 3 o 4) puede eliminar facturas de cualquier cliente asociado a su empresa.
     */
    private function canUserDeleteFacturaOfCompany(int $facturaUserId): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        if ($user->id === $facturaUserId) {
            return true;
        }
        if ($user->role == 1) {
            return true; // Admin
        }
        if ($user->role == 2) {
            return $user->id === $facturaUserId; // Empresa: solo las suyas (redundante con el id check)
        }
        if ($user->role == 3 || $user->role == 4) {
            return $user->clientesAsociados()->where('users.id', $facturaUserId)->exists();
        }
        return false;
    }

    public function DupilicateFacturasRepetidas()
    {
        try {
            $today = Carbon::now();
            $dayOfMonth = $today->day; // Obtener el día actual del mes (1-31)

            $previousMonth = $today->subMonth();
            $year = $previousMonth->year;
            $month = $previousMonth->month;

            // Buscar facturas recurrentes que coincidan con el día actual
            $facturas = Recibo::where('recurrente', 1)
                ->whereYear('fecha', $year) // Crear facturas solo del año 
                ->whereMonth('fecha', $month) // y del mes anterior
                ->where('fecha_recurrente', $dayOfMonth) // que coincidan con el día actual
                ->get();

            $result = [];
            foreach ($facturas as $factura) {
                $result[] = $this->Duplicate($factura->id);
            }

            return response()->json([
                'message' => 'Facturas recurrentes procesadas correctamente',
                'day_processed' => $dayOfMonth,
                'facturas_duplicadas' => count($result),
                'result' => $result
            ], 200);
        } catch (\exception $e) {
            return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
        }
    }

    public function getMailBody()
    {
        return MailPredefinido::where('tipo', 'factura_lote')->first();
    }

    public function Duplicate($id)
    {
        $recibo_c = app(ReciboController::class);
        $factura_old = Recibo::find($id);
        $recibo = Recibo::create(
            [
                'cliente_id' => $factura_old->cliente_id,
                'fecha' => date('Y-m-d'),
                'fecha_tope' => $factura_old->fecha_tope,
                'sub_total' => $factura_old->sub_total,
                'iva' => $factura_old->iva,
                'tipo_iva' => $factura_old->tipo_iva,
                'tipo_descuento' => $factura_old->tipo_descuento,
                'descuento' => $factura_old->descuento,
                'total_descuento' => $factura_old->total_descuento,
                'total' => $factura_old->total,
                'presupuesto_url' => $factura_old->presupuesto_url,
                'factura_url' => $factura_old->factura_url,
                'nota_url' => $factura_old->nota_url,
                'orden_url' => $factura_old->orden_url,
                'has_iva' => $factura_old->has_iva,
                'user_id' => $factura_old->user_id,
                'observaciones' => $factura_old->observaciones,
                'metodo_pago' => $factura_old->metodo_pago,
                'detalle_pago' => $factura_old->detalle_pago,
                'pagado' => 0,
                'recurrente' => 1,
                'fecha_recurrente' => $factura_old->fecha_recurrente, // Mantener la misma fecha recurrente
                'serie_id' => $factura_old->serie_id,
            ]
        );
        //Recibo $recibo, $servicios, $userId
        $recibo_c->saveReciboServicios($recibo, $factura_old->servicios, $recibo->user_id);
        $nombre_archivo = $recibo_c->saveFactura($recibo, 'factura', $recibo->user_id, [], [], null);
        $recibo->factura_url = $nombre_archivo;
        $recibo->save();
        return $recibo;
    }

    public function getFacturas(Request $request, $user_id = null)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $user_id);

        if (!$effectiveUserId) {
            return response()->json([], 200);
        }

        $facturas = Recibo::with(['nro_factura', 'nro_factura_rectificativa', 'cliente', 'serie'])
            ->where('user_id', '=', $effectiveUserId)
            ->orderBy('id', 'DESC');

        if ($request->facturarectificativa) {
            $facturas->whereHas('nro_factura_rectificativa');
        } else {
            $facturas->whereHas('nro_factura');
        }

        if ($request->fecha_desde) {
            $facturas->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->fecha_hasta) {
            $facturas->where('fecha', '<=', $request->fecha_hasta);
        }

        // la busqueda se esta haciendo desde el front 
        /*if($request->search){
        // la busqueda se esta haciendo desde el front 
        /*if($request->search){
        $facturas->where(function ($query) use ($request){
            $query->where('observaciones', 'like', '%'.$request->search.'%');
            $query->orWhereHas('cliente', function ($query2) use ($request){
            $query2->where('nombre', 'like', '%'.$request->search.'%');
            });
        });
        }*/
        $facturas = $facturas->get();
        $facturas_resource = FacturaResource::collection($facturas);

        return response()->json($facturas_resource, 200);
    }

    public function getFacturasProforma(Request $request, $user_id = null)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $user_id);

        if (!$effectiveUserId) {
            return response()->json([], 200);
        }

        $facturas = Recibo::whereHas('nro_factura_prof')
            ->with(['nro_factura_prof' => function ($query) {
                $query->orderBy('nro_factura_prof', 'ASC');
            }])
            ->where('user_id', '=', $effectiveUserId)
            ->orderBy('id', 'DESC')
            ->get();

        $facturas_resource = FacturaResource::collection($facturas);

        return response()->json($facturas_resource, 200);
    }
    public function deleteFactura(Request $request, $factura_id)
    {
        try {
            $factura = Recibo::where('id', $factura_id)
                ->whereHas('nro_factura')
                ->first();

            if (!$factura) {
                return response()->json(['error' => 'Factura no encontrada'], 404);
            }

            if (!$this->canUserDeleteFacturaOfCompany((int) $factura->user_id)) {
                return response()->json(['error' => 'No tiene acceso a eliminar esta factura'], 403);
            }

            DB::transaction(function () use ($factura) {
                if ($factura->factura_url && Storage::disk('recibos')->exists($factura->factura_url)) {
                    Storage::disk('recibos')->delete($factura->factura_url);
                }

                $nroFactura = $factura->nro_factura;
                $deletedNumber = $nroFactura->nro_factura;
                $userId = $nroFactura->user_id;
                $anioId = $nroFactura->id_anio;

                // Eliminar deuda asociada para que no aparezca en pendiente de pago
                if ($nroFactura->deuda) {
                    $nroFactura->deuda()->delete();
                }

                $factura->nro_factura()->delete();

                // Renumerar para que no haya saltos en la numeración
                NroFactura::where('user_id', $userId)
                    ->where('id_anio', $anioId)
                    ->where('nro_factura', '>', $deletedNumber)
                    ->orderBy('nro_factura')
                    ->get()
                    ->each(function ($nf) {
                        $nf->nro_factura = $nf->nro_factura - 1;
                        $nf->save();
                    });

                if ($factura->nro_presupuesto()->exists()) {
                    $factura->factura_url = null;
                    $factura->save();
                    return;
                }

                $factura->servicios()->delete();
                $factura->delete();
            });

            return response()->json('factura eliminada', 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar factura',
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 400);
        }
    }
    public function deleteFacturaProforma(Request $request, $factura_id)
    {
        try {
            $factura = Recibo::where('id', $factura_id)
                ->whereHas('nro_factura_prof')
                ->first();

            if (!$factura) {
                return response()->json(['error' => 'Factura proforma no encontrada'], 404);
            }

            if (!$this->canUserDeleteFacturaOfCompany((int) $factura->user_id)) {
                return response()->json(['error' => 'No tiene acceso a eliminar esta factura proforma'], 403);
            }

            DB::transaction(function () use ($factura) {
                if ($factura->factura_url && Storage::disk('recibos')->exists($factura->factura_url)) {
                    Storage::disk('recibos')->delete($factura->factura_url);
                }

                $nroFacturaProf = $factura->nro_factura_prof;
                $deletedNumber = $nroFacturaProf->nro_factura_prof;
                $userId = $nroFacturaProf->user_id;
                $anioId = $nroFacturaProf->id_anio;

                $factura->nro_factura_prof()->delete();

                // Renumerar para que no haya saltos en la numeración
                NroFacturaProforma::where('user_id', $userId)
                    ->where('id_anio', $anioId)
                    ->where('nro_factura_prof', '>', $deletedNumber)
                    ->orderBy('nro_factura_prof')
                    ->get()
                    ->each(function ($nf) {
                        $nf->nro_factura_prof = $nf->nro_factura_prof - 1;
                        $nf->save();
                    });

                if ($factura->nro_presupuesto()->exists()) {
                    $factura->factura_url = null;
                    $factura->save();
                    return;
                }

                $factura->servicios()->delete();
                $factura->delete();
            });

            return response()->json('factura eliminada', 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar factura proforma',
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 400);
        }
    }
    public function printEmitidas(Request $request, $fecha_inicio, $fecha_fin)
    {
        $user = $request->user();
        $user_id = $user->id;
        $fecha = Carbon::now();
        $desde = Carbon::parse($fecha_inicio);
        $hasta = Carbon::parse($fecha_fin);
        $empresa = 'Autónomo Cristina Gil Mateo';
        $ejercicio = $desde->format('Y');
        $recibos = Recibo::where('fecha', '>=', $desde->format('Y-m-d'))
            ->where('fecha', '<=', $hasta->format('Y-m-d'))
            ->where(function ($query) {
                $query->whereHas('nro_factura');
                //$query->orWhereHas('nro_factura_rectificativa');
            })
            ->where('user_id', '=', $user_id)
            ->orderBy('fecha', 'ASC')
            ->get();
        $data = [
            'fecha' => $fecha,
            'desde' => $desde,
            'hasta' => $hasta,
            'empresa' => $empresa,
            'ejercicio' => $ejercicio,
            'recibos' => $recibos,
        ];
        $nombre_archivo = 'facturas_emitidas' . Carbon::now()->valueof() . '_';

        if ($request->tipo == 2) {
            $nombre_archivo = 'userId_' . $user->id . '/' . $nombre_archivo . '.xlsx';

            Excel::store(new FacturasEmitidasExport($data), $nombre_archivo, 'recibos');
            return "storage/recibos/{$nombre_archivo}";
        } else {
            $nombre_archivo =  $nombre_archivo . '.pdf';
            $view = 'pdf.facturas_emitidas';
            $pdf = PDF::loadView($view, $data)->setPaper('a4', 'landscape');



            Storage::disk('recibos')->put('userId_' . $user->id . '/' . $nombre_archivo, $pdf->output());
            $file_path = Storage::disk('recibos')->path('userId_' . $user->id . '/' . $nombre_archivo);
            //Determinar archivos para gestor documental
            //$archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $user->id, $pdf->output());
            return "storage/recibos/userId_{$user->id}/{$nombre_archivo}";
        }
    }
    public function print(Request $request)
    {
        $ids = [];
        foreach ($request->elementos as $ele) {
            $ids[] = $ele['id'];
        }
        $view = 'pdf.facturas';
        if ($request->resumen) {
            $view = 'pdf.resumen_facturas';
        }
        $facturas = Recibo::whereIn('id', $ids)->get();
        $user = $request->User();
        $logoPath = $user->companyLogoParam()?->value ? 'public/' . $user->companyLogoParam()->value : '';
        $user->logo_base64 = $logoPath ? $this->parseHelper->getLogoBase64($logoPath) : '';
        $signatureBase64 = $user->companySignatureParam()?->value
            ? $this->parseHelper->getLogoBase64('public/' . $user->companySignatureParam()->value)
            : '';
        $data = [];

        foreach ($facturas as $recibo) {
            $data[] = [
                'recibo'              => $recibo,
                'fecha'               => Carbon::parse($recibo->fecha)->format('d-m-Y'),
                'userLog'             => $user,
                'nro_factura'         => ($request->tipo ?? 'factura') == 'factura' ?
                    $recibo->nro_factura->nro_factura :
                    $recibo->nro_factura_rectificativa->nro_factura,
                'tipo'                => $request->tipo ?? 'factura',
                'signature'           => $signatureBase64,
            ];
        }

        $pdf = PDF::loadView($view, ['data' => $data]);

        $nombre_archivo = 'facturas' . Carbon::now()->valueof() . '_'  . '.pdf';
        Storage::disk('recibos')->put('userId_' . $user->id . '/' . $nombre_archivo, $pdf->output());
        $file_path = Storage::disk('recibos')->path('userId_' . $user->id . '/' . $nombre_archivo);
        //Determinar archivos para gestor documental
        //$archivosGestorDocumental = $this->saveFilesForDocumentsApp($nombre_archivo, $recibo, $nro_factura, $tipo, $user->id, $pdf->output());
        return "storage/recibos/userId_{$user->id}/{$nombre_archivo}";
    }
    public function getDataAlbaranes(Request $request, $cliente_id)
    {
        $user = $request->user();
        $albaranesEnviados = AlbaranesEnviado::with('cliente')
            ->where('cliente_id', $cliente_id)
            ->where('user_id', $user['id'])
            ->where('contabilizado', '=', null)
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'status' => 200,
            'albaranesEnviados' => $albaranesEnviados,
        ]);
    }
    public function SendLoteEmail(Request $request)
    {
        $data_email = $request->data_email;

        // Obtener destinatarios CC si existen y no están vacíos
        $destinatarios = [];
        if (isset($data_email['destinatario']) && is_array($data_email['destinatario'])) {
            $destinatarios = array_filter($data_email['destinatario']); // Eliminar elementos vacíos
        }

        // Obtener destinatarios CC si existen y no están vacíos
        $cc = [];
        if (isset($data_email['cc']) && is_array($data_email['cc'])) {
            $cc = array_filter($data_email['cc']); // Eliminar elementos vacíos
        }

        // Obtener destinatarios CCO si existen y no están vacíos
        $bcc = [];
        if (isset($data_email['cco']) && is_array($data_email['cco'])) {
            $bcc = array_filter($data_email['cco']); // Eliminar elementos vacíos
        }

        foreach ($request->elementos as $ele) {
            if (count($destinatarios) > 0) {
                foreach ($destinatarios as $destinatario) {
                    Mail::to($destinatario)
                        // Mail::to('danielalr@fidiaspro.com')
                        ->cc($cc)
                        ->bcc($bcc)
                        ->send(
                            new FacturaLote(
                                'app/public/recibos/userId_' . $request->user()->id . '/' . $ele['nombre_factura'],
                                $request->body,
                                $data_email['asunto'] ?? null
                            )
                        ); //Se envía mail luego de crear el usuario
                }
                return;
            } else {
                if (isset($ele['cliente']['email'])) {
                    if ($ele['cliente']['email'] != null) {
                        Mail::to($ele['cliente']['email'])
                            // Mail::to('danielalr@fidiaspro.com')
                            ->cc($cc)
                            ->bcc($bcc)
                            ->send(
                                new FacturaLote(
                                    'app/public/recibos/userId_' . $request->user()->id . '/' . $ele['nombre_factura'],
                                    $request->body,
                                    $data_email['asunto'] ?? null
                                )
                            ); //Se envía mail luego de crear el usuario
                    }
                }
            }

            $recibo = Recibo::find($ele['id']);
            $recibo->enviado = true;
            $recibo->save();
            // ->update(['enviado'=>1]);
        }
        return response()->json($request->all, 200);
    }
    public function exportExcel(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, $request->user_id);

        if (!$effectiveUserId) {
            return response()->json(["success" => false, "message" => "No tiene acceso a este recurso."], 403);
        }

        // Reemplazar el user_id en los filtros con el effectiveUserId
        $filters = $request->all();
        $filters['user_id'] = $effectiveUserId;

        $type = $request->type;

        // Normalizar el tipo: puede venir como string o como número
        if ($type === "Facturas Recibidas" || $type === "1" || $type === 1) {
            return $this->exportExcelFacturasRecibidas($filters);
        } elseif ($type === "Facturas Enviadas" || $type === "2" || $type === 2) {
            return $this->exportExcelFacturasEnviadas($filters);
        } else {
            return response()->json(["success" => false, "message" => "Tipo no soportado: " . $type], 400);
        }
    }

    /**
     * user_id de la empresa que usa el Excel ampliado (columnas A–S plantilla importación).
     */
    private const USER_ID_EXTENDED_FACTURAS_ENVIADAS_EXCEL = 15;

    private function exportExcelFacturasEnviadas(array $filters)
    {
        $userId = (int) $filters['user_id'];

        $with = $userId === self::USER_ID_EXTENDED_FACTURAS_ENVIADAS_EXCEL
            ? ['nro_factura', 'nro_factura_rectificativa', 'cliente.pais']
            : ['nro_factura', 'cliente'];

        $facturas = Recibo::whereHas('nro_factura')
            ->with($with)
            ->whereBetween('fecha', [$filters['desde'], $filters['hasta']])
            ->where('user_id', '=', $filters['user_id'])
            ->orderBy('id', 'DESC')
            ->get();

        if ($facturas->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No se encontraron facturas enviadas en el rango de fechas especificado."
            ], 404);
        }

        if ($userId === self::USER_ID_EXTENDED_FACTURAS_ENVIADAS_EXCEL) {
            return Excel::download(new FacturasExport($facturas), 'liquidaciones.xlsx');
        }

        return Excel::download(new FacturasExportLegacy($facturas), 'liquidaciones.xlsx');
    }

    private function exportExcelFacturasRecibidas(array $filters)
    {
        $facturas = FacturaRecibida::with("proveedor")
            ->whereBetween("fecha", [$filters["desde"], $filters['hasta']])
            ->where('user_id', '=', $filters['user_id'])
            ->orderBy('id', 'DESC')
            ->get();

        if ($facturas->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No se encontraron facturas recibidas en el rango de fechas especificado."
            ], 404);
        }

        return Excel::download(new FacturasExportRecibidas($facturas), 'liquidaciones.xlsx');
    }
}
