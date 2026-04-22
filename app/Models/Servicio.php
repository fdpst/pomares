<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory, SoftDeletes; 

    protected $table = 'servicio';

    protected $fillable = [
      'nro',
      'descripcion',
      'precio',
      'iva_percent',
      'user_id',
      'venta',
      'id_cuenta_contable',
      'iva_id'
    ];

    protected $dates = ['created_at'];

    protected $casts = [
      'created_at' => 'date:d-m-Y'
    ];
    public function CuentaContable(){
      return $this->hasOne(CuentaContable::class,'id','id_cuenta_contable');
    }
    public function albaranes(){
      return $this->hasMany(Albaran::class);
    }
    public function Iva(){
        return $this->hasOne(Iva::class, 'id', 'iva_id');
    }

    public function precioCambios()
    {
        return $this->hasMany(ServicioPrecioCambio::class, 'servicio_id');
    }
}
