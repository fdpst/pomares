<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordMiddleware
{
    public function handle(Request $request, Closure $next){
      if(!$request->header('X-PASSWORD-TOKEN')){
        return response()->json('header no existe', 401);
      }
      return $next($request);
    }
}
