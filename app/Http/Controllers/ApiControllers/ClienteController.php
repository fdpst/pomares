<?php

namespace App\Http\Controllers\ApiControllers;

use App\Exports\ClientesExport;
use App\Helpers\GestorHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Imports\ClientesImport;
use App\Models\CategoriaCuentaContable;
use App\Models\Cliente;
use App\Models\ClienteHistorial;
use App\Models\CuentaContable;
use App\Models\FormasPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ClienteController extends Controller
{
    public function saveCuentaContableAllClientes()
    {
        $clientes = Cliente::all();
        foreach ($clientes as $cliente) {
            $cuenta = $this->crearCuentaContable(
                [
                    'id_categoria' => 290,

                ],
                $cliente
            );
            $cliente->id_cuenta_contable = $cuenta->id;
            $cliente->save();
        }
    }

    public function getClientes(Request $request, $user_id = null)
    {
        // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
        $effectiveUserId = GestorHelper::getUserId($request, $user_id);
        
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }
        
        $itemsPerPage = $request->amount ?? 15;
        $clientes = Cliente::where('user_id', '=', $effectiveUserId)
            ->where(function ($query) use ($request) {
                if ($request->search && $request->search != "" && gettype($request->search) == 'string') {
                    $query->where('nombre', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('nombre_comercial', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('dni', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('telefono', 'LIKE', '%' . $request->search . '%');
                    /*$query->whereRaw(
                        "MATCH(nombre, nombre_comercial, dni, telefono) AGAINST(\"" . $request->search . "\" IN BOOLEAN MODE)",
                    );*/
                }
            })->orderBy('created_at', 'DESC');

        $totalItems = $clientes->count();
        if($itemsPerPage != -1){
            $clientes = $clientes->paginate($itemsPerPage);
            $response = [
                'data' => ClienteResource::collection($clientes),
                'total' => $totalItems,
                'current_page' => $clientes->currentPage(),
                'last_page' => $clientes->lastPage(),
            ];
        } else {
            $clientes = $clientes->get();
            $response = [
                'data' => ClienteResource::collection($clientes),
                'total' => $totalItems,
                'current_page' => 1,
            ];
        }

        return response()->json($response, 200);
    }

    public function getClienteByid($cliente_id)
    {
        $cliente = Cliente::with('historial', 'cuentaContable')->find($cliente_id);
        return $cliente;
    }

    public function saveCliente(ClienteRequest $request)
    {
        $user = Auth::user();
        
        // Obtener el user_id correcto usando el helper (cliente_id si es gestor)
        // NO pasar $request->user_id como segundo parámetro porque podría ser el user_id del gestor
        $effectiveUserId = GestorHelper::getUserId($request, null);
        
        // RESPUESTA DIRECTA: 
        // Si es gestor (role == 3) y hay cliente seleccionado: usa el ID del cliente seleccionado
        // Si es gestor pero NO hay cliente seleccionado: retorna null (error)
        // Si NO es gestor: usa el ID del usuario autenticado
        
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso. Asegúrate de tener un cliente seleccionado si eres gestor.'], 403);
        }
        
        // Preparar los datos del cliente
        $clienteData = $request->except('historial');
        
        // Sobrescribir el user_id con el correcto (cliente_id si es gestor)
        $clienteData['user_id'] = $effectiveUserId;
        
        $cliente = Cliente::updateOrCreate(['id' => $request->id], $clienteData);
        
        \Log::info('saveCliente - Cliente guardado con ID: ' . $cliente->id . ', user_id: ' . $cliente->user_id);

        if ($request->cuenta_contable) {
            $response = $this->crearCuentaContable($request->cuenta_contable, $cliente);
            $cliente->id_cuenta_contable = $response->id;
            $cliente->cuenta = $response->cuenta;
            $cliente->save();
        }

        return response()->json($cliente, 200);
    }

    private function crearCuentaContable($cuenta_contable, $cliente)
    {
        try {
            $categoria = CategoriaCuentaContable::find($cuenta_contable['id_categoria']);
            $cuenta = str_pad($categoria->cuenta, 9 - strlen(strval($cliente->nro_cliente)), '0') . strval($cliente->nro_cliente);

            $cuenta_c = CuentaContable::updateOrCreate([
                'id' => $cliente->id_cuenta_contable
            ], [
                'numero' => $cuenta,
                'partida' => $cuenta_contable['partida'],
                'id_categoria' => $cuenta_contable['id_categoria']
            ]);

            return $cuenta_c;
        } catch (\Exception $e) {
            return ['code' => 400, 'error' => $e->getMessage()];
        }
    }

    public function saveHistorial($cliente_id, Request $request)
    {
        $cliente = Cliente::find($cliente_id);
        $historial = $cliente->historial()->updateOrCreate(['id' => $request->id], $request->merge(['cliente_id' => $cliente_id])->all());
        return response()->json($historial, 200);
    }

    public function deleteHistorial($historial_id)
    {
        $historial = ClienteHistorial::find($historial_id)->delete();
        return response()->json($historial, 200);
    }

    public function deleteCliente($cliente_id)
    {
        $cliente = Cliente::find($cliente_id);
        $cliente->delete();
        return response()->json($cliente, 200);
    }

    public function formasPago()
    {
        try {
            $formas_pago = FormasPago::all();
            return response()->json(['success' => $formas_pago], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getLastId()
    {
        try {
            $cliente = Cliente::orderBy('nro_cliente', 'DESC')->first();
            $last_id = $cliente?->nro_cliente ?? 0;
            return response()->json(['success' => $last_id], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function exportClientes(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request, null);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $clientes = Cliente::with(['pais', 'provincia'])
            ->where('user_id', $effectiveUserId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nombre', 'LIKE', '%' . $search . '%')
                        ->orWhere('nombre_comercial', 'LIKE', '%' . $search . '%')
                        ->orWhere('dni', 'LIKE', '%' . $search . '%')
                        ->orWhere('telefono', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('nombre')
            ->get();

        $fileName = 'clientes_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ClientesExport($clientes), $fileName);
    }

    public function importClientes(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv',
        ]);

        $effectiveUserId = GestorHelper::getUserId($request, null);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $import = new ClientesImport($effectiveUserId);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'message' => 'Importación finalizada',
            'summary' => $import->getSummary(),
        ], 200);
    }
}
