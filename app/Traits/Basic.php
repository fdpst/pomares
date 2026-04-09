<?php

namespace App\Traits;

trait Basic{

	public static function completarConCero($valor, $n_ceros){
        return str_pad($valor, $n_ceros, "0", STR_PAD_LEFT);
  }

  public static function pathServer(){
        $PATH = $_SERVER['DOCUMENT_ROOT'];
        $pathPublicOut = explode('public',$PATH);
        $res = $pathPublicOut[0];
        return $res;
  }
}
