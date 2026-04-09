<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaLote extends Mailable
{
    use Queueable, SerializesModels;

    public $archivo;
    public $body;
    public $subject;

    public function __construct($archivo, $body, $subject)
    {
        $this->archivo = $archivo;
        $this->body = $body;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->markdown('emails.factura_lote')
                    ->attach(storage_path($this->archivo))
                    ->subject('Factura');
    }
}

