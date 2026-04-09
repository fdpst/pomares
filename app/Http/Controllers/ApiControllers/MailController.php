<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\FacturaMail;
use App\Models\Recibo;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
  public function sendEmail(Request $request){
    try {
        // Ensure the file path is correct
        $filePath = storage_path('app/public/recibos/' . $request->archivo["archivo"]);
        
        // Check if file exists
        if (!file_exists($filePath)) {
            throw new \Exception("El archivo no existe en la ruta: " . $filePath);
        }
        
        $failed = [];
        $success = [];

        $emails = $request->email;
        if(str_contains($emails, ',')){
            $emails = explode(',', $emails);

            foreach($emails as $email){
                try {
                    Mail::to(trim($email))->send(
                        new FacturaMail(
                            $request->subject ?? 'Factura',
                            $request->body ?? 'Envío de documento',
                            $filePath
                        )
                    );
                    $success[] = $email;
                } catch (\Exception $e) {
                    $failed[] = $email;
                    Log::error('Error al enviar el email a ' . $email . ' - ' . $e->getMessage());
                }
    
                usleep(120000);
            }
        }
        else{
            try {
                Mail::to(trim($emails))->send(
                    new FacturaMail(
                        $request->subject ?? 'Factura',
                        $request->body ?? 'Envío de documento',
                        $filePath
                    )
                );
                $success[] = $emails;
            } catch (\Exception $e) {
                $failed[] = $emails;
                Log::error('Error al enviar el email a ' . $emails . ' - ' . $e->getMessage());
            }
        }

        $recibo = Recibo::find($request->id);
        $recibo->enviado = true;
        $recibo->save();

        return response()->json(['message' => 'Email enviado correctamente', 'success' => $success, 'failed' => $failed], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'message' => 'Error al enviar el email', 
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'failed' => $failed,
        ], 400);
    }
  }
}
