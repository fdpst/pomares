<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Correo al punto de venta con la autofactura y el resumen de liquidación en PDF.
 */
class LiquidacionesPuntoVentaMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<int, array{data: string, name: string}> */
    public array $adjuntos;

    public string $asunto;

    /**
     * @param  array<int, array{data: string, name: string}>  $adjuntos
     */
    public function __construct(string $asunto, array $adjuntos)
    {
        $this->asunto = $asunto;
        $this->adjuntos = $adjuntos;
    }

    public function build()
    {
        $mail = $this->view('emails.liquidaciones_punto_venta')
            ->subject($this->asunto);

        foreach ($this->adjuntos as $adj) {
            $mail->attachData($adj['data'], $adj['name'], [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
