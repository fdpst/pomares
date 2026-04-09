<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Requests\RegistroRequest;
use App\Http\Controllers\Controller;
use App\Helpers\FolderHelper;
use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function showRegisterForm(){
      $provincias = Provincia::all();
      return view('registro.register_form', compact('provincias'));
    }

    public function saveRegistro(RegistroRequest $request){
      $user = User::create([
        'name'         => $request->name,
        'email'        => $request->email,
        'telefono'     => $request->telefono,
        'provincia_id' => $request->provincia_id,
        'password'     => bcrypt(Str::random(10)),
        'role'         => 2
      ]);

      if($user){
        $folder_helper = new FolderHelper($user->id);
        $folder_helper->crearCarpetasPrinciales();
      }

      return redirect()->back()->with(['mensaje' => 'Usuario creado con éxito']);
    }
}
