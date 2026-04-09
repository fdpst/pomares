<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes; 

    protected $table = 'proveedores';

    protected $fillable = [
      'nro_proveedor',
      'nombre',
      'email',
      'telefono',
      'user_id',
      'cif',
      'direccion',
      'cp',
      'localidad',
      'id_provincia',
      'id_cuenta_contable',
    ];

    protected $dates = ['created_at'];

    protected $casts = [
      'created_at' => 'date:d-m-Y'
    ];

    public function albaranes(){
      return $this->hasMany(Albaran::class);
    }

    public function cuentaContable(){
      return $this->hasOne(CuentaContable::class,'id','id_cuenta_contable');
    }
}
