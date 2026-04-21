<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReciboEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $archivo;

  public $label;

  public function __construct($archivo){
    $this->archivo = $archivo['archivo'];
    $this->label = $archivo['label'];
  }

  public function build(){
      return $this->markdown('emails.recibo.recibo')
        ->attachFromStorageDisk('recibos', $this->archivo, $this->label)
        ->subject('Fidias Gold | ' . $this->label);
  }
}
