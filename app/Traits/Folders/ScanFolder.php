<?php
    /*
        *Author:  Henry Ruiz : henryruiz332@gmail.com
       
        *version. 1.0
        
            
    */

	namespace App\Traits\Folders;

	use stdClass;
	trait ScanFolder {

        public static function obtenerArchivosDirectorios($extensionFiltro, $rutaDirectorio, $user_id){
            $salida = new stdClass();
            
            $PATH = $_SERVER['DOCUMENT_ROOT'];
            $pathPublicOut = explode('public',$PATH);
            $PATH =  $pathPublicOut[0]; //Nuevo path
            $directorio = $PATH . $rutaDirectorio;
           

            $explode =  explode("/", $directorio); 
            $carpeta =  $explode[count($explode)-1];
            $carpetaOriginal =  $explode[count($explode)-1];
            

            $ficheros_leidos  = scandir($directorio, 1);

            $explodeParent = explode("/", $rutaDirectorio);


            $parentDirectory = $explodeParent[count($explodeParent)-2];

            if ($parentDirectory == 'userId_'.$user_id) {
                $parentDirectory = null;
            }

            if($carpeta == 'documentacion_general'){
                $carpeta = 'Documentación General';
            }
            if($carpeta == 'factura'){
                $carpeta = 'Facturas Emitidas';
            }

            if($carpeta == 'factura_recibidas'){
                $carpeta = 'Facturas Recibidas';
            }
            
            sort($ficheros_leidos);
            $ficheros= array();
            $archivos = [];
            $sinHora = '';
            $e=0;
            $n=0;
            for($e=0;$e<count($extensionFiltro);$e++){
                for($f=0;$f<count($ficheros_leidos);$f++){
                    $extension_archivo = explode('.',$ficheros_leidos[$f]);
                    if(isset($extension_archivo[1])){
                    $extension_archivo = $extension_archivo[1];
                    
                    if($extension_archivo==$extensionFiltro[$e])
                        {
                            $ficheros[$n]=$ficheros_leidos[$f];
                            $sinExtension =explode('.',$ficheros_leidos[$f]);
                            $sin_piso = explode('_',$sinExtension[0]);

                            $sinHora =  $sin_piso;
                           
                            $nombreNuevo = $sin_piso[0];
                            for ($i=2; $i < count($sin_piso) ; $i++) { 
                            $nombreNuevo = $nombreNuevo.' '.$sin_piso[$i];
                            }
                            $archivos[$n]= $nombreNuevo;
                            
                            $n++;
                        }
                    }  
                }
            }

            $salida->href = $ficheros;
            // $salida->archivos = $archivos;
            $salida->parentPholder = $parentDirectory;
            $salida->path = $directorio; 
            $salida->childrens = Array(); 
            $salida->nombreCarpeta = $carpeta;
            $salida->carpetaOriginal = $carpetaOriginal;
            $salida->nombreSinHora = $sinHora;
            return $salida;
            return json_encode($salida);
        }
    }