<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRole
{
   public function handle($request, Closure $next, ...$roles){
      foreach($roles as $role) {
        if($request->user()->role == $role){
            return $next($request);
        }
      }
      return response()->json('NO TIENE PERSMISOS', 401);
   }
}
