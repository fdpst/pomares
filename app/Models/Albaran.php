<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
use File;

class Albaran extends Model
{
    use HasFactory;

    protected $table = 'albaranes';

    protected $fillable = [
      'fecha',
      'descripcion',
      'proveedor_id',
      'imagen',
      'user_id',
      'pdf'
    ];

    protected $dates = ['fecha', 'created_at'];

    public function proveedor(){
      return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function setImagenAttribute($imagen){
      if(!$imagen || !File::isFile($imagen)){
    		return null;
    	}
    	$file_name = uniqid() . '-' . $imagen->getClientOriginalName();
      Storage::disk('albaranes')->put($file_name,  File::get($imagen));
    	$this->attributes['imagen'] = $file_name;
    }
}
