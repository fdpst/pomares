<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomCrypt extends Facade {
   protected static function getFacadeAccessor() { return 'customcrypt'; }
}
