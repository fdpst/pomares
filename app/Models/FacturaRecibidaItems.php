<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaRecibidaItems extends Model
{
    use HasFactory;

    public $table = "factura_recibidas_items";

    protected $fillable = [
        "factura_recibidas_id",
        "concepto",
        "cantidad",
        "id_servicio",
        "precio",
        "dcto",
        "iva",
        "total"
    ];
    public function Factura(){
        return $this->hasOne(FacturaRecibida::class,'id','factura_recibidas_id');
    }
    public function Servicio(){
        return $this->hasOne(Servicio::class,'id','id_servicio');
      }
}
