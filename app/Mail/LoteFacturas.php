<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoteFacturas extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(){

    }

    public function build(){
      return $this->markdown('emails.recibo.facturas')
        ->attachFromStorageDisk('lotes', 'facturas.zip', 'facturas.zip')
        ->subject('Facturas');
    }
}
