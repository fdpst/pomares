<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotaLote extends Mailable
{
    use Queueable, SerializesModels;
    public $archivo;
    public function __construct($archivo){
      $this->archivo = $archivo;
    }

    public function build(){
      return $this->markdown('emails.nota_lote')
      ->attach(storage_path($this->archivo))
        ->subject('Nota');
    }
}
