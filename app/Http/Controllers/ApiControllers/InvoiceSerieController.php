<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\InvoiceSerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GestorHelper;

class InvoiceSerieController extends Controller
{
    public function index(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        
        if (!$effectiveUserId) {
            return response()->json([], 200);
        }
        
        return response()->json(
            InvoiceSerie::where("user_id", $effectiveUserId)->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            InvoiceSerie::where('id', $id)->first()
        );
    }

    public function store(Request $request)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }
        
        return response()->json(
            InvoiceSerie::create([
                "serie" => $request->serie,
                "user_id" => $effectiveUserId
            ])
        );
    }

    public function update(Request $request, $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }
        
        $serie = InvoiceSerie::where('id', $id)->where('user_id', $effectiveUserId)->first();
        
        if (!$serie) {
            return response()->json(['error' => 'Serie no encontrada o sin permisos'], 404);
        }
        
        return response()->json(
            $serie->update([
                "serie" => $request->serie
            ])
        );
    }

    public function destroy(Request $request, $id)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }
        
        $serie = InvoiceSerie::where('id', $id)->where('user_id', $effectiveUserId)->first();
        
        if (!$serie) {
            return response()->json(['error' => 'Serie no encontrada o sin permisos'], 404);
        }
        
        return response()->json(
            $serie->delete()
        );
    }
}
