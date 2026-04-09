<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// START cambios para recordar contraseña con envio de una nueva por mail 
use App\Models\User;
use CustomCrypt;
use App\Mail\NewPassMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Users\UserRequest;
// END cambios para recordar contraseña con envio de una nueva por mail 


class AuthController extends Controller
{
   public function login(Request $request)
   {
      try {
         $user = User::where('email', $request->email)->first();

         if (!$user) {
            return response()->json(['message' => ['Usuario no encontrado.']], 401);
         }

         if (!Hash::check($request->password, $user->password)) {
            return response(['message' => ['Contraseña incorrecta.']], 401);
         }

         $token = $user->createToken('my-app-token')->plainTextToken;

         $userAbilityRules = [
            [
               'action' => 'manage',
               'subject' => 'all'
            ]
         ];
         $response = [
            'user' => $user,
            'token' => $token,
            'userAbilityRules' => $userAbilityRules
         ];

         return response($response, 200);
      } catch (\Exception $e) {
         return response()->json([
            'message' => 'Error al iniciar sesión.',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'status' => 400
         ], 400);
      }
   }

   public function logout(Request $request)
   {
      if ($request->user()) {
         return $request->user()->token()->delete();
      }
      return response()->json('logout', 200);
   }

   public function changePassword(Request $request)
   {
      $user = User::where('email', $request->email)->first();

      if (!$user) {
         return response()->json(['tipo' => 'email', 'message' => ['Usuario no encontrado.']], 401);
      }

      if (!Hash::check($request->old_password, $user->password)) {
         return response(['tipo' => 'old_password', 'message' => ['Contraseña incorrecta.']], 401);
      }

      if ($request->new_password != $request->confirm_password) {
         return response(['tipo' => 'confirm_password', 'message' => ['Confirmar contraseña no coincide.']], 401);
      }

      $user->password = bcrypt($request->new_password);
      $user->save();

      return response()->json('success', 200);
   }
   // START cambios para recordar contraseña con envio de una nueva por mail
   public function recoverPassword(UserRequest $request)
   {
      $mail = $request->email;
      if (empty($mail)) {
         return response()->json(['tipo' => 'email', 'message' => ['El email es obligatorio.']], 400);
      }

      $user = User::where('email', $mail)->first();
      if (!$user) {
         return response()->json(['tipo' => 'email', 'message' => ['Usuario no encontrado.']], 401);
      }

      try {
         $password = Str::random(10);
         $user->password = bcrypt($password);
         $user->update();
         Mail::to($mail)->send(new NewPassMail($user, $password));
         return response()->json($user, 200);
      } catch (\Throwable $th) {
         return response()->json([
            'tipo' => 'email',
            'message' => ['Error al enviar el email de recuperación. Contacte con el administrador.'],
            'error' => config('app.debug') ? $th->getMessage() : null,
         ], 500);
      }
   }
   // END cambios para recordar contraseña con envio de una nueva por mail 
}
