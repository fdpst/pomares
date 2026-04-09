<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class SymLinkController extends Controller
{
    public function create(){
       $exitCode = Artisan::call('storage:link');
       return response("Command finished with exit code ${exitCode}");
    }

    public function getVideo($video){
      $video = Video::find($video)->first();
      $fileContents = Storage::disk('videos')->get($video->file_name);
      $response = Response::make($fileContents, 200);
      $response->header('Content-Type', "video/mp4");
      return $response;
    }

    public function ordenSepa(){
      $pdf = PDF::loadView('pdf.orden.orden_sepa');
      return $pdf->stream();
    }
}
