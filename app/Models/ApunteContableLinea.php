<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApunteContableLinea extends Model
{
    use HasFactory;

    public $table = 'apunte_contable_linea';

    protected $fillable = [ 'cuenta_contable_id', 'apunte_contable_id', 'documento', 'descripcion', 'debe', 'haber' ];

    public function apunteContable(){
        return $this->hasOne(ApunteContable::class,'id','apunte_contable_id');
    }

    public function cuentaContable(){
        return $this->hasOne(CuentaContable::class,'id','cuenta_contable_id');
    }
}
