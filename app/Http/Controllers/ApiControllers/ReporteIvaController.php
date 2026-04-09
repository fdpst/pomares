<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApunteContable;


class ReporteIvaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int $anio
     * @return \Illuminate\Http\Response
     */
    public function index($anioSelect)
    {

        $semestreUno = $this->selectIva("$anioSelect", '-01-01', '-03-31');
        $semestreDos = $this->selectIva("$anioSelect", '-04-01', '-06-30');
        $semestreTres = $this->selectIva("$anioSelect", '-07-01', '-09-30');
        $semestreCuatro = $this->selectIva("$anioSelect", '-10-01', '-12-31');

        $sub_total_ventas = DB::table('apunte_contable_linea')
        ->join('apunte_contable','apunte_contable_linea.apunte_contable_id','apunte_contable.id')
        ->where('apunte_contable.fecha', 'LIKE', "$anioSelect%")
        ->where('apunte_contable_linea.descripcion','LIKE', 'Venta%')
        // ->select(DB::raw('SUM(debito) as sub_total_ventas'))
        ->select(DB::raw('SUM(apunte_contable_linea.debe) as sub_total_ventas'))
        ->get();

        $sub_total_compras = DB::table('apunte_contable_linea')
        ->join('apunte_contable','apunte_contable_linea.apunte_contable_id','apunte_contable.id')
        ->where('apunte_contable.fecha', 'LIKE', "$anioSelect%")
        ->where('apunte_contable_linea.descripcion','LIKE', 'Compra%')
        // ->select(DB::raw('SUM(debito) as sub_total_ventas'))
        ->select(DB::raw('SUM(apunte_contable_linea.haber) as sub_total_compras'))
        ->get();

        return response()->json(['code' => 200, 'success' => [
            "semestreUno" => $semestreUno,
            "semestreDos" => $semestreDos,
            "semestreTres" => $semestreTres,
            "semestreCuatro" => $semestreCuatro,
            "sub_total_ventas" => $sub_total_ventas[0]->sub_total_ventas,
            "sub_total_compras" => $sub_total_compras[0]->sub_total_compras,
        ]]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $anio
     * @param  string  $semestre_inicio
     * @param  string  $semestre_fin
     * @return Array $semestre
     */
    public function selectIva($anio, $semestre_inicio, $semestre_fin)
    {
        $semestre = DB::table('apunte_contable_linea')
        ->join('apunte_contable', 'apunte_contable_linea.apunte_contable_id','apunte_contable.id')
        ->join('cuenta_contable', 'apunte_contable_linea.cuenta_contable_id','cuenta_contable.id')
        ->where('apunte_contable_linea.descripcion','LIKE','Impuesto')
        ->whereBetween('apunte_contable.fecha', ["$anio"."$semestre_inicio", "$anio"."$semestre_fin"])
        ->where(function ($query) {
            $query->where('cuenta_contable.partida', 'LIKE', '%repercutido%')
                ->orWhere('cuenta_contable.partida', 'LIKE', '%soportado%');
        })
        ->select(DB::raw('SUM(apunte_contable_linea.debe) as total_iva_soportado'), DB::raw('SUM(apunte_contable_linea.haber) as total_iva_repercutido'))
        ->get();

        return $semestre;
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Array $anios
     */
    public function selectAnio()
    {
        $anios_db = ApunteContable::select('fecha')
                ->groupBy('fecha')
                ->get();


        $anios = [];
        for ($i=0; $i <= count($anios_db) - 1; $i++) {
            $fecha = date_create($anios_db[$i]->fecha);
            $fecha = date_format($fecha, "Y");
            $anios[] = $fecha;
        }

        $anios = array_unique($anios);
        $aniosF = [];
        for ($i=0; $i <= count($anios); $i++) {
            if(isset($anios[$i])){
                $aniosF[] = $anios[$i];
            }
        }

        return response()->json(['code' => 200, 'success' => $aniosF]);
    }

}
