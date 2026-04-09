<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

// Rutas de preview para pruebas de diseño - DEBEN IR ANTES DE LA RUTA CATCH-ALL
// La autenticación se maneja dentro de los métodos del controlador
Route::middleware(['web'])->group(function () {
    Route::get('preview-albaran/{albaranId}', '\App\Http\Controllers\ApiControllers\AlbaranController@previewAlbaran');
    Route::get('preview-recibo/{reciboId}', '\App\Http\Controllers\ApiControllers\ReciboController@previewRecibo');
});

Route::get('registro', 'UsuarioController@showRegisterForm');
Route::post('registro', 'UsuarioController@saveRegistro')->name('save.registro');

/* Route::get('/', function () {
    return view('base');
}); */

Route::get('symlink', 'SymLinkController@create');
Route::get('orden-sepa', 'SymLinkController@ordenSepa');

// Ruta para descargar archivos ZIP de facturas - DEBE IR ANTES DE LA RUTA CATCH-ALL
Route::get('/client-files/{token}/{name}', function ($token,$name)
{
    $user = User::where('filetoken',$token)->first();
    if($user == null ) {
        return response()->json(['error' => 'Link Expirado'], 404);
    }

    $filePath = 'userId_'.$user->id.'/'.$name;

    // Verificar si el archivo existe usando exists() en lugar de path()
    if(!Storage::disk('lotes')->exists($filePath)){
        return response()->json(['error' => 'File not found'], 404);
    }
    
    $contents = storage_path('app/public/lotes/userId_'.$user->id.'/'.$name);
    
    // Verificar que el archivo existe físicamente antes de intentar descargarlo
    if(!file_exists($contents)) {
        return response()->json(['error' => 'File not found'], 404);
    }
    
    return response()->download($contents);
});

Route::get('crear-directorio', function(){
  return Storage::makeDirectory('public/lotes');
});

// Ruta catch-all - debe ir al final y excluir preview-albaran, preview-recibo y client-files
Route::get('/{any}', function () {
    return view('base');
})->where('any', '^(?!.*(?:api|storage|preview-albaran|preview-recibo|client-files)).*$');
Route::get('/clear', function() {
   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');
   return "Cleared!";
});
