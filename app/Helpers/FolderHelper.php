<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FolderHelper
{
    public $user_id;

    public function __construct($user_id){
      $this->user_id = $user_id;
   }

   public function crearCarpetasPrinciales(){
      $principal = ['documentacion_general', 'factura', 'factura_recibidas'];

      foreach ($principal as $value) {
          $path = "public/documentos/userId_{$this->user_id}/{$value}";
          Storage::makeDirectory($path);
          chmod(storage_path('app/' . $path), 0775);
      }

      $paths = [
          "public/users/userId_{$this->user_id}",
          "public/recibos/userId_{$this->user_id}",
          "public/lotes/userId_{$this->user_id}",
          "public/albaranes/enviados/userId_{$this->user_id}",
          "public/albaranes/recibidos/userId_{$this->user_id}"
      ];

      foreach ($paths as $path) {
          Storage::makeDirectory($path);
          chmod(storage_path('app/' . $path), 0775);
      }
  }
}
