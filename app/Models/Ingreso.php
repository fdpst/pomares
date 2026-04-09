<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;

    protected $table = 'ingresos';

    protected $fillable = [
      'codigo',
      'importe',
      'descripcion',
      'created_at',
      'user_id'
    ];

    protected $dates = ['created_at'];

    public function setCodigoAttribute($codigo){
      $this->attributes['codigo'] = mb_strtoupper($codigo);
    }
}
