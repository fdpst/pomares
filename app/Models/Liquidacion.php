<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    use HasFactory;

    public $table = 'liquidaciones';

    protected $fillable = [
        'fecha',
        'nro_factura',
        'user_id',
        'proveedor_id',
        'retencion_id',
        'descripcion',
        'imagen',
        'total',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items()
    {
        return $this->hasMany(LiquidacionItem::class, 'liquidacion_id', 'id');
    }
}
