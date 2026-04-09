<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MailLotesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        
        // Validar y obtener email del remitente
        $fromEmail = $data['user']['email'] ?? config('mail.from.address');
        $fromName = $data['user']['name'] ?? config('mail.from.name');
        
        // Validar que el email del remitente no sea null
        if (empty($fromEmail)) {
            $fromEmail = config('mail.from.address');
        }
        
        // Validar que hay emails destinatarios
        if (empty($data['emails']) || !is_array($data['emails'])) {
            Log::error('MailLotesJob: No hay emails destinatarios', ['data' => $data]);
            return;
        }
        
        // Construir asunto
        $subjectName = $data['user']['name'] ?? 'Usuario';
        $subjectFiscal = $data['user']['nombre_fiscal'] ?? '';
        $subject = $subjectName . ($subjectFiscal ? ' , ' . $subjectFiscal : '');
        
        foreach($data['emails'] as $email){
            // Validar que el email destinatario no sea null o vacío
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('MailLotesJob: Email destinatario inválido', ['email' => $email]);
                continue;
            }
            
            try {
                Mail::send('emails.lotes', $data, function ($message) use ($fromEmail, $email, $subject){
                    $message->from($fromEmail)
                            ->to($email)
                            ->subject($subject);
                });
                
                Log::info('MailLotesJob: Email enviado exitosamente', [
                    'from' => $fromEmail,
                    'to' => $email
                ]);
            } catch (\Exception $e) {
                Log::error('MailLotesJob: Error enviando email', [
                    'from' => $fromEmail,
                    'to' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }
        /*if($data['tipo'] == 'Facturas Enviadas'){
            if(Storage::disk('lotes')->path('userId_'.$data['user']['name'].'/facturas_enviadas.zip')){

            foreach($data['emails'] as $email){
            Mail::send('emails.lotes', $data, function ($message) use ($data, $email){
                    $message->from($data['user']['email']); //From
                    $message->to($email)->subject($data['user']['name'] .' , '. $data['user']['nombre_fiscal'])
                    ->attach(storage_path('app/public/lotes/userId_' . $data['user']['id'] . '/facturas_enviadas.zip'));

                });
            }
        }
        }

        if($data['tipo'] == 'Facturas Recibidas'){
            foreach($data['emails'] as $email){
                if(Storage::disk('lotes')->path('userId_'.$data['user']['name'].'/facturas_recibidas.zip')){
                    Mail::send('emails.lotes', $data, function ($message) use ($data, $email){
                    $message->from($data['user']['email']); //From
                    $message->to($email)->subject($data['user']['name'] .' , '. $data['user']['nombre_fiscal'])
                    ->attach(storage_path('app/public/lotes/userId_' . $data['user']['id'] . '/facturas_recibidas.zip'));

                });
            }
            }
        }


        if($data['tipo'] == 'Todas'){
            foreach($data['emails'] as $email){
            Mail::send('emails.lotes', $data, function ($message) use ($data, $email){
                    $message->from($data['user']['email']); //From
                    $message->to($email)->subject($data['user']['name'] .' , '. $data['user']['nombre_fiscal'])
                    ->attach(storage_path('app/public/lotes/userId_' . $data['user']['id'] . '/facturas_recibidas.zip'))
                    ->attach(storage_path('app/public/lotes/userId_' . $data['user']['id'] . '/facturas_enviadas.zip'))
                    
                    ;

                });
            }
        }*/
        

       
    }
}
