<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\DB;
use App\Models\Deuda;
use App\Models\TiposGasto;
use App\Models\Gasto;
use App\Models\Ingreso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GestorHelper;

class EstadisticasController extends Controller
{
  public function getIngresoBruto(Request $request, $user_id = null)
  {
    // Usar el helper para obtener el user_id correcto (cliente_id si es gestor)
    $effectiveUserId = GestorHelper::getUserId($request);

    if (!$effectiveUserId) {
      return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
    }

    if ($request->has(['desde', 'hasta'])) {
      $suma_gastos = GestorHelper::applyUserIdScope(Gasto::query(), $request)
        ->whereBetween('created_at', [$request->desde, $request->hasta])
        ->sum('importe');

      $tiposGasto = GestorHelper::applyUserIdScope(TiposGasto::query(), $request)->orderBy('id', 'DESC')->get();
      $suma_gastos_desglosado = array();
      foreach ($tiposGasto as $tipoGasto) {
        $suma_gastos_desglosado[$tipoGasto->nombre] = GestorHelper::applyUserIdScope(Gasto::query(), $request)
          ->where('tipo_id', $tipoGasto->id)
          ->whereBetween('created_at', [$request->desde, $request->hasta])
          ->sum('importe');
      }

      $suma_ingresos = GestorHelper::applyUserIdScope(Ingreso::query(), $request)
        ->whereBetween('created_at', [$request->desde, $request->hasta])
        ->sum('importe');

      $suma_deuda = GestorHelper::applyUserIdScope(Deuda::query(), $request)
        ->whereBetween('created_at', [$request->desde, $request->hasta])
        ->select(DB::raw('sum(total - pagado) as total'))
        ->get();

      $deudaTotal = $suma_deuda[0]['total'] ? $suma_deuda[0]['total'] : 0;
      /////////////////////////////////////////////////////////////////////////////////////
      $gastos = GestorHelper::applyUserIdScope(Gasto::query(), $request)->whereBetween('created_at', [$request->desde, $request->hasta])->get();
      $ingresos = GestorHelper::applyUserIdScope(Ingreso::query(), $request)->whereBetween('created_at', [$request->desde, $request->hasta])->get();
      //$deuda = Deuda::whereBetween('created_at', [$request->desde, $request->hasta])->when(\App\Helpers\GestorHelper::restrictQueriesByOwnerUserId(), function ($q) use ($request) { return $q->where('user_id', \App\Helpers\GestorHelper::getUserId($request)); })->select(DB::raw('sum(total - pagado) as total'))->get();
      $deudas = GestorHelper::applyUserIdScope(Deuda::query(), $request)->whereBetween('created_at', [$request->desde, $request->hasta])->get();
      //consultamos los proyectos en el intervalo de tiempo pedido
      //$proyectos = Proyecto::whereBetween('fecha_alta', [$desde, $hasta])->get();
      //tomamos el valor numerico de los meses
      $mesdesde = date('m', strtotime($request->desde));
      $meshasta = date('m', strtotime($request->hasta));
      //declaramos los arreglos para los totales que necesitaremos
      $total_totales[] = [];
      $total_gastos[] = [];
      $total_ingresos[] = [];
      $total_deudas[] = [];
      //definimos el volumen de los arreglos en base al valor numerico de los meses
      for ($i = $mesdesde - 1; $i < $meshasta; $i++) {
        $total_totales[$i] = 0;
        $total_gastos[$i] = 0;
        $total_ingresos[$i] = 0;
        $total_deudas[$i] = 0;
      }
      //creamos un ciclo para rellenar los totales de cada uno por mes
      foreach ($gastos as $key => $gasto) {
        $mes = date("m", strtotime($gasto->created_at));
        $total_gastos[$mes - 1] = $total_gastos[$mes - 1] + $gasto->importe;
        $total_totales[$mes - 1] = $total_totales[$mes - 1] - $gasto->importe;
      }
      foreach ($ingresos as $key => $ingreso) {
        $mes = date("m", strtotime($ingreso->created_at));
        $total_ingresos[$mes - 1] = $total_ingresos[$mes - 1] + $ingreso->importe;
        $total_totales[$mes - 1] = $total_totales[$mes - 1] + $ingreso->importe;
      }
      foreach ($deudas as $key => $deuda) {
        $mes = date("m", strtotime($deuda->created_at));
        $total_deudas[$mes - 1] = $total_deudas[$mes - 1] + ($deuda->total - $deuda->pagado);
      }
      //incluimos todo en un nuevo arreglo, junto a los nombres de los meses
      for ($i = $mesdesde - 1; $i < $meshasta; $i++) {
        $labels = [
          'Enero',
          'Febrero',
          'Marzo',
          'Abril',
          'Mayo',
          'Junio',
          'Julio',
          'Agosto',
          'Septiembre',
          'Octubre',
          'Noviembre',
          'Diciembre'
        ];
        $estadisticaTotales[$i] = [
          'Mes' => $labels[$i],
          'Totales' => $total_totales[$i],
          'Ingresos' => $total_ingresos[$i],
          'Gastos' => $total_gastos[$i],
          'Deudas' => $total_deudas[$i],
        ];
      }

      return response()->json([
        'gasto_desglosado' => $suma_gastos_desglosado,
        //'gasto_interno' => $suma_gastos_internos,
        'gasto'         => $suma_gastos,
        'ingreso'       => $suma_ingresos,
        'suma_deuda'    => $deudaTotal,
        'estadisticas'  => array_values($estadisticaTotales),
      ], 200);
    }

    return response()->json('error', 301);
  }
}
