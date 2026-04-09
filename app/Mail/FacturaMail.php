<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaMail extends Mailable  implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $filePath;

    public function __construct($subject, $body,$filePath){
        $this->subject = $subject;
        $this->body = $body;
        $this->filePath = $filePath;
    }

    public function build(){
        return $this->markdown('emails.lotes', ['body' => $this->body])
        ->attach($this->filePath)
        ->subject($this->subject);
    }
}
