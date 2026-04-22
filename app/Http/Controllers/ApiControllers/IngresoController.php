<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngresoRequest;
use App\Models\Ingreso;
use App\Models\Deuda;
use App\Models\NroFactura;
use App\Models\NroNota;
use App\Models\Recibo;
use App\Models\AnioFiscal;
use Illuminate\Support\Facades\DB;
use App\Helpers\GestorHelper;

class IngresoController extends Controller
{
    /**
     * @see PRIMERA VEZ QUE Jancker Sepulveda Modifica este archivo, error al crear ingreso exento
     * @see de ser efecto colateral del trabajo del mismo 
     */
    public function getIngresos(Request $request, $user_id = null)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json([], 200);
        }

        if ($request->has(['desde', 'hasta'])) {
            $ingresos = GestorHelper::applyUserIdScope(Ingreso::query(), $request)
                ->orderBy('created_at', 'DESC')
                ->whereBetween('created_at', [$request->desde, $request->hasta])
                ->get();
            return response()->json($ingresos, 200);
        }

        $ingreso = GestorHelper::applyUserIdScope(Ingreso::query(), $request)->orderBy('created_at', 'DESC')->get();
        return response()->json($ingreso, 200);
    }

    public function getIngresoById(Request $request, $ingreso_id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $ingreso = GestorHelper::applyUserIdScope(Ingreso::query()->where('id', $ingreso_id), $request)->first();

        if (!$ingreso) {
            return response()->json(['error' => 'Ingreso no encontrado'], 404);
        }

        return response()->json($ingreso, 200);
    }

    /**
     * Metodo para guardar un ingreso.
     *
     * @param  \App\Http\Requests\IngresoRequest  $request
     * @return \App\Models\Ingreso;
     */
    public function saveIngreso(IngresoRequest $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $request->merge(['user_id' => $effectiveUserId]);

        $codigo_to_upper = mb_strtoupper($request->codigo);
        $codigo_inicio = substr($codigo_to_upper, 0, 3);
        $importe = (float) $request->importe;

        if (in_array($codigo_inicio, [mb_strtoupper('FAC'), mb_strtoupper('NOT')], true)) {
            $modelName = $codigo_inicio === mb_strtoupper('FAC') ? 'nro_factura' : 'nro_nota';
            $columnName = $modelName;
            $tipo = $codigo_inicio === mb_strtoupper('FAC') ? 'FAC' : 'NOT';
            $numero_factura = ltrim(str_replace($tipo, '', $codigo_to_upper), '0') ?: '0';
            $nro = $this->getFacturaOrNota($modelName, $columnName, $numero_factura, $effectiveUserId);
            if ($nro && $nro->deuda) {
                $deudaPendiente = $nro->deuda->total - $nro->deuda->pagado;
                $maxImporte = $deudaPendiente;
                if ($request->id) {
                    $ingresoExistente = GestorHelper::applyUserIdScope(Ingreso::query()->where('id', $request->id), $request)->first();
                    if ($ingresoExistente) {
                        $maxImporte = $deudaPendiente + (float) $ingresoExistente->importe;
                    }
                }
                if ($importe > $maxImporte) {
                    return response()->json([
                        'message' => 'El ingreso no puede superar el total pendiente de pago.',
                        'errors' => ['importe' => ['El importe no puede ser mayor que ' . number_format($maxImporte, 2, ',', '.') . ' €.']],
                    ], 422);
                }
            }
        }

        $ingreso = Ingreso::updateOrCreate(['id' => $request->id], $request->all());

        if ($codigo_inicio == mb_strtoupper('FAC')) {
            $this->asociarFactura($codigo_to_upper, 'nro_factura', 'nro_factura', 'FAC', $effectiveUserId);
        }

        if ($codigo_inicio == mb_strtoupper('NOT')) {
            $this->asociarFactura($codigo_to_upper, 'nro_nota', 'nro_nota', 'NOT', $effectiveUserId);
        }

        return response()->json('Ingreso guardado', 200);
    }

    /**
     * Metodo para asociar ingreso a una factura.
     *
     * @param string $codigo_to_upper
     * @param string $model_name
     * @param string $column_name
     * @param string $tipo
     * @return void;
     */
    public function asociarFactura(String $codigo_to_upper, String $model_name, String $column_name, String $tipo, String $user)
    {
        $extraer_cod = str_replace($tipo, '', $codigo_to_upper);
        $numero_factura = ltrim($extraer_cod, '0');

        // Obtener la factura o nota
        $nro_factura = $this->getFacturaOrNota($model_name, $column_name, $numero_factura, $user);
        $current_deuda = $nro_factura?->deuda;

        // Validar si se encontró la factura/nota
        if (!$current_deuda) {
            return null;
        }

        // Validar si tiene deuda asociada
        $current_deuda = $nro_factura->deuda;
        if (!$current_deuda) {
            return null;
        }

        $suma_ingresos = $this->sumaIngresos($tipo, $numero_factura, $user);

        return $this->updateDeuda($current_deuda, $suma_ingresos, $user);
    }

    /**
     * Suma los importes de ingresos que pertenecen a la misma factura/nota.
     * Solo se cuentan códigos cuyo número normalizado (quitar tipo y ceros a la izquierda)
     * coincide con $numero_factura, para no mezclar factura 1 con 10, 11, etc.
     *
     * @param string $tipo Ej: 'FAC', 'NOT'
     * @param string $numero_factura Número de factura sin ceros a la izquierda (ej: '1', '10')
     * @param string $user
     * @return float
     */
    private function sumaIngresos(String $tipo, $numero_factura, String $user)
    {
        return (float) Ingreso::where('user_id', $user)
            ->whereRaw("TRIM(LEADING '0' FROM REPLACE(codigo, ?, '')) = ?", [$tipo, (string) $numero_factura])
            ->sum('importe');
    }

    private function updateDeuda(Deuda $current_deuda, $suma_ingresos)
    {
        $current_deuda->pagado = min($suma_ingresos, $current_deuda->total);
        $current_deuda->save();

        // Marcar o desmarcar el recibo como pagado para que aparezca el tick en lista-facturas
        $recibo = $current_deuda->deuda?->recibo;
        if ($recibo instanceof Recibo) {
            $recibo->pagado = $current_deuda->pagado >= $current_deuda->total;
            $recibo->save();
        }

        return $current_deuda;
    }

    /**
     * Busca la factura o nota por número y usuario.
     * Prioriza la que tiene deuda (aparece en pendientes de pago) y la más reciente por año.
     * Así el ingreso se asocia a la factura correcta aunque sea de un año fiscal anterior.
     */
    private function getFacturaOrNota($model_name, $column_name, $numero_factura, $user)
    {
        $nro = null;

        if ($model_name == 'nro_factura') {
            $nro = NroFactura::where($column_name, $numero_factura)
                ->where('user_id', $user)
                ->whereHas('deuda')
                ->orderBy('id_anio', 'Desc')
                ->first();
        }

        if ($model_name == 'nro_nota') {
            $nro = NroNota::where($column_name, $numero_factura)
                ->where('user_id', $user)
                ->whereHas('deuda')
                ->orderBy('id_anio', 'Desc')
                ->first();
        }

        return $nro;
    }

    public function deleteIngreso(Request $request, $ingreso_id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);

        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $ingreso = GestorHelper::applyUserIdScope(Ingreso::query()->where('id', $ingreso_id), $request)->first();

        if (!$ingreso) {
            return response()->json(['error' => 'Ingreso no encontrado'], 404);
        }

        $codigo = $ingreso->codigo;

        $ingreso->delete();

        $codigo_to_upper = mb_strtoupper($codigo);

        $codigo_inicio = substr($codigo_to_upper, 0, 3);

        if ($codigo_inicio == mb_strtoupper('FAC')) {
            $this->asociarFactura($codigo_to_upper, 'nro_factura', 'nro_factura', 'FAC', $ingreso->user_id);
        }

        if ($codigo_inicio == mb_strtoupper('NOT')) {
            $this->asociarFactura($codigo_to_upper, 'nro_nota', 'nro_nota', 'NOT', $ingreso->user_id);
        }

        return response()->json('Ingreso eliminado', 200);
    }
}
