<?php

namespace App\Providers;

use App;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider {

   public function boot() {

   }

   public function register() {
      App::bind('customcrypt',function() {
         return new \App\Helpers\CustomCrypt;
      });
   }
}
