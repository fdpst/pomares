<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApunteContable extends Model
{
    use HasFactory;

    public $table = 'apunte_contable';

    protected $fillable = [
        'fecha', 'tipo_apunte_id', 'apunte_predefinido_id', 'cliente_id', 'factura_id', 
        'proveedor_id', 'factura_recibida_id', 'nota', 'debito', 'credito'
    ];

    public function tipoApunte(){
        return $this->hasOne(TipoApunte::class,'id','tipo_apunte_id');
    }
    public function Importes(){
        return $this->hasMany(ApunteContableImporte::class,'id_apunte','id');
    }

    public function Lineas(){
        return $this->belongsTo(ApunteContableLinea::class,'id','apunte_contable_id');
        // foreach($lineas as $linea){
        //      $linea -> subtotal = $linea->precio * $linea->cantidad;
        //      $linea -> total = $linea -> subtotal - $linea->dcto;    
        // }
    }

    public function factura(){
        return $this->hasOne(Recibo::class,'id','factura_id');
    }

    public function facturaEntrante(){
        return $this->hasOne(FacturaRecibida::class,'id','factura_recibida_id');
    }
}
