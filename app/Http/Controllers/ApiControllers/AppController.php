<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AppController extends Controller
{

    protected function pathServer(){
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public',$PATH);
        $res = $pathPublicOut[0]; 
        return $res;
    }

    public function main(Request $request, $idUser){

        $albaranes = $this->pathServer() . 'storage/app/public/albaranes/';
        $albaranesEnviados = $this->pathServer() . 'storage/app/public/albaranes/enviados/';
        $albaranesRecibidos = $this->pathServer() . 'storage/app/public/albaranes/recibidos/';
        $recibos = $this->pathServer() . 'storage/app/public/recibos/';

        $notas = $this->pathServer() . 'storage/app/public/documentos/userId_' . $idUser . '/nota/';
        $lotes = $this->pathServer() . 'storage/app/public/lotes/';
        $lotesUser = $this->pathServer() . 'storage/app/public/lotes/userId_' . $idUser . '/';



        $directorios = [
            'albaranes' =>  $albaranes,
            'albaranesEnviados' =>  $albaranesEnviados,
            'albaranesRecibidos' =>  $albaranesRecibidos,
            'recibos' =>  $recibos,
            'notas' =>  $notas,
            'lotes' =>  $lotes,
            'lotesUser' => $lotesUser,
        ];

        foreach ($directorios as $key => $directorio) {
            $this->ValidateDir($directorio);
        }
       


        //make link storage in app
        Artisan::call('storage:link');
        return response()->json([

            'message' => 'En ejecución normal'
        ]);

    }
    public function ValidateDir($dir){
        if(!is_dir($dir)){
            mkdir($dir, 0755);
        }else{
            return "Directorio existente";
        }
    }
}
