<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    protected $table = 'deuda';

    protected $fillable = [    
      'total',
      'pagado',
      'deuda_id',
      'deuda_type',
      'user_id'
    ];

    public function deuda(){
      return $this->morphTo();
    }

    /*public function nro_factura_table(){
      return $this->belongsTo(NroFactura::class, 'nro_factura_id');
    }*/
}
