<?php

namespace App\Http\Controllers\ApiControllers;

use PDF;
use stdClass;
use App\Models\User;
use DirectoryIterator;
use PharIo\Manifest\Author;
use Illuminate\Http\Request;
use App\Models\GestorDocumental;
use App\Traits\Files\HandlerFiles;
use App\Traits\Folders\ScanFolder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Mockery\Undefined;
use App\Helpers\GestorHelper;

class GestorDocumentalController extends Controller
{

    /**
     * pathServer(): retorna la raíz del proyecto
     *
     * rutas():  retorna rutas de carpetas por usuario 
     */ 
    protected function pathServer(){
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public',$PATH);
        $res = $pathPublicOut[0]; 
        return $res;
    }

    protected function rutas($user_id){
        $all = Storage::allDirectories('/public/documentos/userId_' . $user_id);
        $newAll = [];
        foreach($all as $route){
            // Comentar o descomentar la ruta que proceda segun sea el entorno

            //$newAll[] =  'storage/app/' .$route; // ruta para funciona en local
            
             $newAll[] =  '/laravel/storage/app/' .$route; // Ruta para funciona en produccion
        }
        return  $newAll;
    }

    protected function rutasSinStorage($user_id){
        return $all = Storage::allDirectories('/public/documentos/userId_' . $user_id);
    }
    public function arrayFormatos(){
        $formats = ["pdf", "PDF","png","PNG","html","HTML", "webp","WEBP","jpg", "JPG","jpeg","JPEG", "xlsx","XLSX", "xls","XLS", "svg","SVG", "docx", "DOXC","doc","DOC","ppt","PPT","pptx","PPTX","txt","TXT", "gif","GIF","csv","CSV","zip","ZIP","gz","GZ","rar","RAR","tar","TAR","xml","XML","xmls","XMLS", "mp3", "mp4", "json", "epub", "EPUB", "ibook", "IBOOK", "awz",
            ];
        return $formats;
    }

    public function mainLevels(){
        $arrayConsnt = ['Documentación General' => "documentacion_general", 
                        'Facturas Emitidas' => 'factura', 
                        'Facturas Recibidas' => 'factura_recibidas'];
        return $arrayConsnt;
    }

     /**
     * Crear carpetas en según user_id
     * mkdir
     * 
     */ 
    protected function createFolder(Request $request){    
        $pathRequest = $request->path;
        $folderName = $request->folderName;
        $level = $request->level;
        $pathServ = $this->pathServer();
        $replaceSpecials = preg_replace('([^A-Za-z0-9 ])', ' ',  $folderName); //Reemplaza caracteres especiales por espacios
        $replaceSpecials = str_replace(" ", "_" ,$replaceSpecials);//reemplaza espacios por pisos
        $folderName = $replaceSpecials;
        if(!is_dir($pathRequest. '/'. $folderName)){
            mkdir($pathRequest. '/'. $folderName, 0777);
        }else{
            return "La carpeta existe";
        }
        return response()->json([
            'status' => 200,
            'message' => 'Carpeta Creada',
            'level'  => $level,
            'newFolder' => $folderName,
        ]);       
    }

    
     /**
     * Función principal. Lista los documentos de carpetas
     *
     * 
     */ 
    public function getDocumentos(Request $request, $user_id = null)
    {
        $effectiveUserId = GestorHelper::getUserId($request);
        if (!$effectiveUserId) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }

        $path = $this->pathServer();
        
        $allScan = [];
        $arrayRutas = $this->rutas($effectiveUserId);
        foreach($arrayRutas as $ruta){           
            // if($ruta == 'storage/app/public/documentos/userId_'.$user_id.'/factura'){
            //     $factFiles[] =  ScanFolder::obtenerArchivosDirectorios($this->arrayFormatos(), $ruta, $user_id);
            // }
            // if($ruta == 'storage/app/public/documentos/userId_'.$user_id.'/facturas_recibidas'){
            //     $factRecivedFiles[] =  ScanFolder::obtenerArchivosDirectorios($this->arrayFormatos(), $ruta, $user_id);
            // }
            $busqueda = $ruta;
            if(is_dir($path.$ruta)){
                $allScan [] = ScanFolder::obtenerArchivosDirectorios($this->arrayFormatos(),$busqueda, $effectiveUserId);
            }
        }
        return response()->json([
            'status' => 200,
            'todosLosArchivos' => $allScan
        ]);
    }

    public function saveDocuments(Request $request, $files = null)
    {
        $carpeta = $request->carpeta;
        $destination = $request->path . '/';
        $store = HandlerFiles::store($request,  $destination);
        return response()->json([
            'destination' => $request->path . '/'
        ]);
    }

    /**
     * Eliminar documentos según user_id
     * 
     * unlink
     * 
     */ 
    public function deleteDocument(Request $request){
        $user_id = GestorHelper::getUserId($request);
        if (!$user_id) {
            return response()->json(['error' => 'No tiene acceso a este recurso'], 403);
        }
        $arrayRutas = $this->rutas($user_id);
        $PATH = $this->pathServer();
        $tree =  json_decode($request->tree);
        for ($i=0; $i < count($tree) ; $i++) { 
            if($tree[$i]->isFolder == "true"){
                $this->deleteGlobalFolder($PATH, $tree[$i]->path, $user_id);
            }else{
                unlink($tree[$i]->path.'/'.$tree[$i]->name);
            } 
        }
        return response()->json([
            'status' => 200,
            'message' => 'Documento Borrado',
        ]);
    }

    public function deleteGlobalFolder($PATH, $pathDocument, $user_id){
        $allDirects =  Storage::allDirectories('public/documentos/userId_' . $user_id);
        foreach ($allDirects as $aEliminar) {
            if ($pathDocument == $PATH . 'storage/app/'.$aEliminar) {
                $scanDir = scandir($PATH. 'storage/app/'.$aEliminar);
                $deletePath = $PATH. 'storage/app/'.$aEliminar;
                foreach($scanDir as $file) {
                    if ('.' === $file || '..' === $file) continue;
                    if (is_dir("$deletePath/$file")){
                        rmdir("$deletePath/$file");
                    }else{
                        unlink("$deletePath/$file");
                    }
                }
                rmdir($deletePath);
           }
        }
    }

    public function viewDocument(Request $request){
        return $request;
        $documentRoute = $this->filtersFiles($request->documento,  $request->format, $request->user_id);
        return response()->json([
            'status' => 200,
            'message' => "Ok",
            'documentRoute' => $documentRoute,
        ]);
    }

    public function filtersFiles($namefile, $format, $user_id){
        $fileName = $namefile;
        $format = null;
        $PATH = $this->pathServer();
        $carpetaDeUsuario = $PATH. 'storage/app/public/documentos/userId_' . $user_id;
        if (file_exists($carpetaDeUsuario)) {
            $carpetaDeUsuarioStorageMethod = Storage::allDirectories('public/documentos/userId_' . $user_id);
            $saveFolders =  Array();
            foreach ($carpetaDeUsuarioStorageMethod as  $first) {
                $firstExplode = explode('public', $first);
                $secondExplode = explode('documentos', $firstExplode[1]);
                $threExplode = explode('userId_' . $user_id, $secondExplode[1]);
                $fourExplode = explode('/', $threExplode[1]);
                $fiveExplode = explode('""', $fourExplode[1]);
                $saveFolders[] = $fiveExplode;
            }
            $outArray = Array();
            foreach ($saveFolders as $key => $array) {
                foreach ($array as $keys => $value) {
                    $outArray[count($outArray)]= $value;
                }
            }
            $scanFilesOnDir = $outArray; 
            foreach ($scanFilesOnDir as $dir) {
                foreach(scandir($carpetaDeUsuario.'/'.$dir) as $file) {
                    if($file == $fileName){
                        //return Storage::download($carpetaDeUsuario.'/'.$dir . '/'. $file);
                        // return response()->download($carpetaDeUsuario.'/'.$dir . '/'. $file);
                        return response()->json([
                            'status' => 200,
                            'message' => "ok",
                            'routeCompleta' =>  $dir . '/'. $file,
                            'routeDownload' =>  $file,
                            'file' => $format,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Gestion listados en backend admin tareas
     * 
     * No salen los admins
     * 
     */ 
    
    public function adminGestionDocumental(){
        // $user = User::orderBy('created_at', 'DESC')->paginate(15);
        $user = User::orderBy('created_at', 'DESC')->where('role','2')->paginate(15);
         foreach ($user as  $value) {
            if ($value['role'] == 1) {
                $value['role'] = 'Administrador';
            }
            if ($value['role'] == 2) {
                $value['role'] = 'Cliente';
            }
         }
        return response()->json([
            'status' => 200,
            'message' => 'Ok',
            'users' => $user,
        ]);
    }
}