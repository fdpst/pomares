<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use File;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'gastos';

    protected $fillable = [
      'codigo',
      'importe',
      'descripcion',
      'created_at',
      'archivo',
      'user_id',
      'tipo_id',
    ];

    protected $dates = ['created_at'];

    public function tipo(){
      return $this->belongsTo(TiposGasto::class, 'tipo_id');
    }

    // public function setArchivoAttribute($imagen){
    //   if(!$imagen || !File::isFile($imagen)){
    //     return null;
    //   }
    //   $file_name = uniqid() . '-' . $imagen->getClientOriginalName();
    //   Storage::disk('gastos')->put($file_name,  File::get($imagen));
    //   $this->attributes['archivo'] = $file_name;
    // }
}
