<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Pais;

class ProvinciaController extends Controller
{
  public function getProvincias(Request $request, $user_id= null){
    //return response()->json(Provincia::where('user_id', '=', $user_id)->orderBy('nombre')->get(['id', 'nombre']), 200);
    //return response()->json(Provincia::orderBy('nombre')->get(['id', 'nombre']), 200);
    
    // START cambios para clientes con Pais
    return response()->json(Provincia::orderBy('nombre')->get(['id', 'nombre','id_pais']), 200);
  }

  public function getPaises(Request $request){
    return response()->json(Pais::All());
  }
    // END cambios para cientes con Pais
}
