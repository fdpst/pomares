<?php

namespace App\Http\Controllers\ApiControllers;

use App\Imports\CategoriaCuentaContableImport;
use App\Models\CategoriaCuentaContable;
use App\Models\FacturaRecibida;
use App\Models\Iva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ApunteContable;
use App\Models\CuentaContable;
use App\Models\TipoApunte;
use App\Models\ApunteContableImporte;
use App\Models\ApuntePredefinido;
use App\Models\ApunteContableLinea;
use App\Models\Recibo;
use App\Http\Resources\ApunteContableResource;
use App\Http\Resources\FacturaLibroDiarioResource;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ApunteContableController extends Controller
{
    public function getApuntes(Request $request){
        try{
            $busqueda = $request->busqueda;
            $fecha_desde = $request->fecha_desde;
            $fecha_hasta = $request->fecha_hasta;
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $page = $request->rowsPerPage ? $request->rowsPerPage : 10;

            $apuntes = ApunteContableLinea::with(['apunteContable.tipoApunte']);

            if($busqueda){
                $apuntes->where(function ($query) use ($busqueda){
                    $query->where('descripcion','LIKE','%'.$busqueda.'%');
                    // $query->orWhereHas('cuentaContable.Nombre',function ($query2) use ($busqueda){
                    //     $query2->where('nombre','LIKE','%'.$busqueda.'%');
                    // });
                    // $query->orWhereHas('cuentaContable.Apellido',function ($query3) use ($busqueda){
                    //     $query3->where('nombre','LIKE','%'.$busqueda.'%');
                    // });
                });
            }
    
            if($fecha_desde){
                $apuntes->whereHas('apunteContable', function ($query) use ($fecha_desde) {
                    $query->whereDate('fecha', '>=', $fecha_desde);
                });
            } 
            if($fecha_hasta) {
                $apuntes->whereHas('apunteContable', function ($query) use ($fecha_hasta) {
                    $query->whereDate('fecha', '<=', $fecha_hasta);
                });
            }
            if($fecha_inicio) {
                $apuntes->whereHas('apunteContable', function ($query) use ($fecha_inicio) {
                    $query->whereDate('fecha', '>=', $fecha_inicio);
                });
            }
            if($fecha_fin) {
                $apuntes->whereHas('apunteContable', function ($query) use ($fecha_fin) {
                    $query->whereDate('fecha', '<=', $fecha_fin);
                });
            }

            $total = $apuntes->count();
            $apuntes = $apuntes->orderBy('id', 'DESC')->paginate($page);
            $data = ApunteContableResource::collection($apuntes);

            return response()->json(['code' => 200, 'success' => [
                'data'=>$data,
                'total'=>$total
            ]]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function saveAsiento(Request $request){
        try{
            $apunte = ApunteContable::updateOrCreate(
                ['id' => $request->id],
                [
                    'fecha' => $request->fecha,
                    'tipo_apunte_id' => $request->tipo_apunte_id,
                    'apunte_predefinido_id' => $request->apunte_predefinido_id,
                    'cliente_id' => $request->cliente_id,
                    'factura_id' => $request->factura_id,
                    'proveedor_id' => $request->proveedor_id,
                    'factura_recibida_id' => $request->factura_recibida_id,
                    'nota' => $request->nota,
                ]
            );
            $this->saveImportes($request->importes??[],$apunte);
            $response = $this->saveLineas($request->cuentas, $apunte);
            if($response['code'] == 200){
                $total_debe = ApunteContableLinea::where('apunte_contable_id', $apunte->id)->sum('debe');
                $total_haber = ApunteContableLinea::where('apunte_contable_id', $apunte->id)->sum('haber');

                $apunte->debito = $total_debe;
                $apunte->credito = $total_haber;
                $apunte->save();

                return response()->json(['code' => 200, 'success' => 'Creacion exitosa']);
            }else{
                return response()->json(['code' => 400, 'error' => $response['error']],400);
            }
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }
    private function saveImportes($importes ,$apunte){
        $ids = [];
        foreach($importes as $impote){
            $ids[] = ApunteContableImporte::updateOrCreate(['id'=>$impote['id']??null],[
                'id_apunte'=>$apunte->id,
                'iva'=>$impote['iva'],
                'subtotal'=>$impote['subtotal'],
                'importe_iva'=>$impote['importe_iva'],
            ])->id;
        }
        ApunteContableImporte::where('id_apunte',$apunte->id)->whereNotIn('id',$ids)->delete();
    }
    
    private function saveLineas($lineas, $apunte){
        try{
            if(isset($lineas)){
                $ids = [];
                foreach($lineas as $linea){
                    $id = null;
                    if(isset($linea['id'])){
                        $id = $linea['id'];
                    }

                    $apunte_linea = ApunteContableLinea::updateOrCreate(
                        [ 'id' => $id ],
                        [
                            'cuenta_contable_id' => $linea['cuenta_contable_id'] ?? null,
                            'apunte_contable_id' => $apunte->id,
                            'documento' => $linea['documento'],
                            'descripcion' => $linea['descripcion'],
                            'debe' => $linea['debe'],
                            'haber' => $linea['haber'],
                        ]
                    );

                    $ids[] = $apunte_linea->id;
                }

                ApunteContableLinea::where('apunte_contable_id', $apunte->id)->whereNotIn('id', $ids)->delete();
            }

            return ['code' => 200, 'success' => true];
        }catch(\Exception $e){
            return ['code' => 400, 'error' => $e->getMessage()];
        }
    }

    public function deleteAsiento($id){
        try{
            $apunte = ApunteContable::find($id);
            $apunte->delete();

            ApunteContableLinea::where('apunte_contable_id', $id)->delete();

            return response()->json(['code' => 200, 'success' => 'Asiento eliminado']);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function deleteAsientoLinea($id){
        try{
            ApunteContableLinea::where('id', $id)->delete();

            return response()->json(['code' => 200, 'success' => 'Asiento eliminado']);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getAsiento($id){
        try{
            $apunte_lineas = ApunteContableLinea::with(['apunteContable.facturaEntrante', 'apunteContable.factura','apunteContable.importes'])->find($id);

            // Obtén la información del asiento contable desde la relación
            $apunteContable = isset($apunte_lineas->apunteContable) ? $apunte_lineas->apunteContable : null;
            $factura = isset($apunteContable->factura) ? $apunteContable->factura : null;
            $facturaEntrante = isset($apunteContable->facturaEntrante) ? $apunteContable->facturaEntrante : null;

            if(isset($apunteContable)){
                // Organizar los datos en el formato deseado
                $response = [
                    'id' => $apunteContable->id,
                    'fecha' => $apunteContable->fecha,
                    'tipo_apunte_id' => $apunteContable->tipo_apunte_id,
                    'apunte_predefinido_id' => $apunteContable->apunte_predefinido_id,
                    'cliente_id' => $apunteContable->cliente_id,
                    'factura_id' => $apunteContable->factura_id,
                    'proveedor_id' => $apunteContable->proveedor_id,
                    'factura_recibida_id' => $apunteContable->factura_recibida_id,
                    'nota' => $apunteContable->nota,
                    'debito' => $apunteContable->debito,
                    'credito' => $apunteContable->credito,
                    'documento' => $apunteContable->documento,
                    'factura' => $factura,
                    'importes'=>$apunteContable->importes,

                    'factura_entrante' => $facturaEntrante,
                    'cuentas' => ApunteContableLinea::with(['cuentaContable'])
                                ->where('apunte_contable_id', $apunteContable->id)
                                ->get(),
                ];
            }

            $factura_id = null;
            $factura_recibida_id = null;
            foreach($response['cuentas'] as $element){
                if(isset($response['factura_id'])){
                    $factura_id = $apunteContable->factura_id;
                }

                if(isset($response['factura_recibida_id'])){
                    $factura_recibida_id = $apunteContable->factura_recibida_id;
                }
                $element->factura_id = $factura_id;
                $element->factura_recibida_id = $factura_recibida_id;
            }

            return response()->json(['code' => 200, 'success' => $response]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getCuentas(Request $request){
        try{
            $cuentas = CuentaContable::select('id', 'numero')->get();
            return response()->json(['code' => 200, 'success' => $cuentas]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getTipos(Request $request){
        try{
            $tipos = TipoApunte::all();
            return response()->json(['code' => 200, 'success' => $tipos]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getPrdefinidos(Request $request){
        try{
            $predefinidos = ApuntePredefinido::all();
            return response()->json(['code' => 200, 'success' => $predefinidos]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getFacturasCliente(Request $request){
        try{
            $facturas = Recibo::with(['servicios', 'cliente.cuentaContable'])->where('cliente_id', $request->contacto_id)->get();
            $data = FacturaLibroDiarioResource::collection($facturas);
            return response()->json(['code' => 200, 'success' => $data]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getFacturasProveedor(Request $request){
        try{
            $facturas = FacturaRecibida::with(['items', 'proveedor.cuentaContable'])->where('proveedor_id', $request->contacto_id)->get();
            $data = FacturaLibroDiarioResource::collection($facturas);
            return response()->json(['code' => 200, 'success' => $data]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    // Trae las cuentas contables saegun el prefijo que indica o caracteriza la cuenta (sea para cliente, producto o proveedor)
    public function getCuentasByPrefix($prefix){
        try{
            $cuentas = CuentaContable::select('id', 'numero')->where('numero', 'like', $prefix.'%')->get();
            return response()->json(['code' => 200, 'success' => $cuentas]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getIva(Request $request){
        try{
            $iva = Iva::all();
            return response()->json(['code' => 200, 'success' => $iva]);
        }catch(\Exception $e){
            return response()->json(['code' => 400, 'error' => $e->getMessage()]);
        }
    }

    public function getCategoriasCuentas(Request $request){
        try{
            $categorias = CategoriaCuentaContable::all();
            return response()->json(['success' => $categorias], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // funcion usada para importar documentos de excel a la db (llamada desde postman ya que no se realizo una interfaz grafica para esto)
    public function importFromExcel(Request $request){
        try{
            // Validar que se haya enviado un archivo
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls',
            ]);

            // Obtener el archivo enviado
            $file = $request->file('file');

            Excel::import(new CategoriaCuentaContableImport(), $file);  // cambiar la clase Import por la que se requiera, 
                                                                        // en este caso se importan las categorias de cuenta contable

            return response()->json(['success' => 'Los datos han sido importados correctamente.'], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
