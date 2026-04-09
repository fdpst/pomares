<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\RequestTokenMail;
use App\Models\PasswordToken;
use Illuminate\Http\Request;
use App\Models\Password;
use App\Models\User;
use Carbon\Carbon;
use CustomCrypt;
use Auth;
use Mail;

class PasswordController extends Controller
{
  public function signToken(Request $request){
    if (!Hash::check($request->password, auth()->user()->password)) {
       return response(['message' => ['Contraseña incorrecta.']], 401);
    }

    $token = [
      'user_id' => auth()->user()->id,
      'codigo'  => random_int(1000, 9999),
      'expired' => Carbon::now()->addHour()
    ];

    Mail::to(auth()->user()->email)
      ->send(new RequestTokenMail($token));

    PasswordToken::updateOrCreate(['user_id' => auth()->user()->id, 'tipo' => 0], [
      'token' => base64_encode(json_encode($token))
    ]);

    return base64_encode(json_encode($token));
  }

  public function verificarCodigo(Request $request){
    $token = PasswordToken::where('user_id', auth()->user()->id)
      ->where('tipo', 0)
      ->get()
      ->first();

    $token_decode = (json_decode(base64_decode($token->token), true));

    if($token_decode['codigo'] == $request->codigo){
      return response()->json([
        'message' => 'Código verificado con exito',
        'token'   => $token->token
      ], 200);
    }
    return response()->json(['message' => 'Código incorrecto'], 401);
  }

  public function getPasswords(){
    $passwords = Password::orderBy('created_at', 'DESC')->get();
    return response()->json($passwords, 200);
  }

  public function getPassword($id){
    $password = Password::where('id', $id)->get(['id', 'cuenta', 'password', 'detalle'])->makeVisible(['password', 'real_password'])->first();
    return response()->json($password, 200);
  }

  public function savePassword(Request $request){
    $password = Password::updateOrCreate(['id' => $request->id],
    [
      'cuenta'  => $request->cuenta,
      'detalle' => $request->detalle,
      'password' => CustomCrypt::Encrypt($request->real_password)
    ]);
    return $password;
  }

  public function deletePassword($id){
    Password::find($id)->delete();
    return response()->json(['mensaje' => 'Item eliminado'], 200);
  }
}
