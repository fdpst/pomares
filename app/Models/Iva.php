<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iva extends Model
{
    use HasFactory;

    public $table = 'iva';

    public function CuentaContableRepercutido(){
        return $this->hasOne(CuentaContable::class,'id','id_cuenta_contable_repercutido');
    }
    public function CuentaContableSoportado(){
        return $this->hasOne(CuentaContable::class,'id','id_cuenta_contable_soportado');
    }
}
