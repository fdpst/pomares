<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Proveedor;



class FacturaRecibida extends Model
{
    use HasFactory;

    public $table = "factura_recibidas";

    protected $fillable = [
      "fecha",
      "nro_factura",
      "user_id",
      "proveedor_id",
      "retencion_id",
      "descripcion",
      "imagen",
      "total"
    ];

    public function proveedor(){
      return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items(){
      return $this->hasMany(FacturaRecibidaItems::class, 'factura_recibidas_id', 'id');
    }

}
