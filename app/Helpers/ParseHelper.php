<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManagerStatic as Image;

class ParseHelper
{
    public function parseEuroNumber($value)
    {
        // Si el valor es nulo, devolvemos nulo
        if(is_null($value)) return null;

        // Si el valor es un número, lo devolvemos directamente
        if(is_numeric($value)) return floatval($value);

        // Si el valor contiene una coma, reemplazamos el punto por una coma y convertimos a float
        if(str_contains($value, ',')) {
            // Primero eliminamos los puntos (separadores de miles)
            $value = str_replace('.', '', $value);
            // Luego reemplazamos la coma por un punto para el decimal
            $value = str_replace(',', '.', $value);
            return floatval($value);
        }

        // Si el valor no contiene una coma pero sí puntos, eliminamos los puntos (son separadores de miles)
        if(str_contains($value, '.')) {
            return floatval(str_replace('.', '', $value));
        }

        // Si el valor no contiene ni coma ni punto, convertimos a float directamente
        return floatval($value);
    }

    /**
     * Obtiene la ruta absoluta del logo para usar directamente en el PDF
     * Esto evita cargar la imagen completa en memoria como base64
     * 
     * @param string $logo_url Ruta relativa del logo (ej: public/users/userId_2/avatar_69171f3c1c4b5.png)
     * @return string Ruta absoluta del archivo o string vacío si no existe
     */
    public function getLogoPath(string $logo_url){
        try {
            if (empty($logo_url)) {
                return '';
            }

            // El avatar se almacena como "public/users/userId_X/avatar_XXX.png" en la BD
            // Laravel usa storage link: public/storage -> storage/app/public
            // La ruta real del archivo es: storage/app/public/users/userId_X/avatar_XXX.png
            
            // Si la ruta ya incluye "public/", removerlo y usar storage_path
            if (strpos($logo_url, 'public/') === 0) {
                $logo_url = str_replace('public/', '', $logo_url);
            }
            
            $logo_path = storage_path('app/public/' . $logo_url);

            if (!file_exists($logo_path)) {
                // Intentar también con public_path por si está en el directorio public directamente
                $public_path = public_path($logo_url);
                if (file_exists($public_path)) {
                    return $public_path;
                }
                return '';
            }

            return $logo_path;
        } catch (\Exception $e) {
            Log::error('Error al obtener la ruta del logo: ' . $e->getMessage());
            return '';
        }
    }

    public function getLogoBase64(string $logo_url){
        try {
            if (empty($logo_url)) {
                return '';
            }

            // Usar la misma lógica que getLogoPath para encontrar el archivo
            // El avatar se almacena como "public/users/userId_X/avatar_XXX.png" en la BD
            // Laravel usa storage link: public/storage -> storage/app/public
            // La ruta real del archivo es: storage/app/public/users/userId_X/avatar_XXX.png
            
            // Si la ruta ya incluye "public/", removerlo y usar storage_path
            if (strpos($logo_url, 'public/') === 0) {
                $logo_url = str_replace('public/', '', $logo_url);
            }
            
            $logo_path = storage_path('app/public/' . $logo_url);

            if (!file_exists($logo_path)) {
                // Intentar también con public_path por si está en el directorio public directamente
                $public_path = public_path($logo_url);
                if (file_exists($public_path)) {
                    $logo_path = $public_path;
                } else {
                    return '';
                }
            }

            $binaryData = file_get_contents($logo_path);
            if ($binaryData === false) {
                return ''; // Retornar string vacío si no se puede leer el archivo
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($binaryData);
            return 'data:' . $mimeType . ';base64,' . base64_encode($binaryData);
        } catch (\Exception $e) {
            Log::error('Error al procesar el logo: ' . $e->getMessage());
            return ''; // Retornar string vacío en caso de error
        }
    }
}
