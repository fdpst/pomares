<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    protected $table = 'cuenta_contable';
    use HasFactory;
    protected $fillable = [
        'numero',
        'partida',
        'id_categoria',
    ];
    
    // public function Nombre(){
    //     return $this->hasOne(MetodoNombre::class,'id','metodo_nombre')->select(['id','nombre']);
    // }

    // public function Apellido(){
    //     return $this->hasOne(MetodoApellido::class,'id','metodo_apellido')->select(['id','nombre']);
    // }
    
    // public function CuentaContableTotales(){
    //     return $this->hasMany(CuentaContableTotales::class,'id_cuenta_contable','id');
    // }
}
