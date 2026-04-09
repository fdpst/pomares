<?php

namespace App\Traits\Files;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use stdClass;

trait HandlerFiles {

        public static function images($extensionFiltro, $rutaDirectorio){

        }
        /*
            *Guardado de documentos servidor / base de datos
            *$request ->Archivos
            *$destination ->Ubicación de la carpeta donde se guardará el archivo
            *$destination ->El valor destination puede ser enviado desde cualquier controlador
        */
        public static function store($request, $destination)
        {

            $images = HandlerFiles::uploadFiles($request, $destination);

            $nombresDeArchivos = [];
            
            foreach ($images as $imageFile) {

                list($fileName, $title) = $imageFile;

                // $image->producto_id = $request->productoId;
                // $image->alt = $request->alt;
                // $image->orden = 0;
                $split = explode('/', $fileName);
                $lastFilename = end($split);
                $split2 = explode('\\',$lastFilename);
                $endFileName = end($split2);
                $nombresDeArchivos[] = $endFileName;
                // $image->imagen = $endFileName;

                // $image->save();
            }

            // $imagenP = ProductoImagen::where('producto_id', $request->productoId)->first();

            // $imagenP->orden = 1;
            // $imagenP->update();


            return response()->json([
                'status' => 200,
                'message' => "Los documentos se han cargado",
                 'images' => $images,
                'nombresArchivos' => $nombresDeArchivos
            ]);

        }

        public static function uploadFiles($request, $destination){
            $uploadImages = [];
            if ($request->hasFile('imagen')) {
                $images = $request->file('imagen');

                foreach ($images as $image) {
                    $uploadImages[] = HandlerFiles::uploadFile($image, $destination);
               }

            }
            return $uploadImages;
        }
        public static function uploadFile($image, $destination){


            // $destination = $_SERVER['DOCUMENT_ROOT'];
            // $splitDestiation = explode('public',$destination);
            // $destination = $splitDestiation[0] . 'storage/app/public/documentos/';
            //application/vnd.openxmlformats-officedocument.spreadsheetml.sheet

            // Ensure destination directory exists and has correct permissions
            if (!is_dir($destination)) {
                @mkdir($destination, 0775, true);
            }
            // Normalize trailing directory separator
            $lastChar = substr($destination, -1);
            if ($lastChar !== '/' && $lastChar !== '\\') {
                $destination .= DIRECTORY_SEPARATOR;
            }

            $originalFileName= $image->getClientOriginalName();
            $extension      = $image->getClientOriginalExtension();
            $filenameOnly = pathinfo($originalFileName, PATHINFO_FILENAME);
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $fileName =  Str::slug($filenameOnly, '') . '_'. time() . '.' . $extension;



            $extensions = HandlerFiles::extensionsImage();
            foreach ($extensions as $extension) {
                if($extension == $fileExtension){
                    $img = Image::make($image);
                    $tiempo = 10* filesize($image);
                    set_time_limit($tiempo);
                    $img->resize(600, 600, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });

                    set_time_limit(30);
                    $img->save($destination.$fileName);
                    @chmod($destination.$fileName, 0644);


                    // $uploadedFileName = $image->move($destination,$fileName, 777);
                    $uploadedFileName = $fileName;
                    return [$uploadedFileName, $filenameOnly];
                }else{

                    $uploadedFileName = $image->move($destination,$fileName, 0775);
                    // $uploadedFileName can be a SplFileInfo; normalize to file name
                    $movedPath = is_object($uploadedFileName) ? $uploadedFileName->getPathname() : ($destination.$fileName);
                    @chmod($movedPath, 0644);
                    return [$fileName, $filenameOnly];
                }
            }



        }



        public static function extensionsImage(){
            $arrayExtension = ['png', 'jpg', 'jpeg', 'svg', 'PNG', 'JPG', 'JPEG', 'SVG', 'WEBP', 'web'];
            return $arrayExtension ;
        }

    }
