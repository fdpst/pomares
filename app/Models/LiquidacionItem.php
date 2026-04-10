<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidacionItem extends Model
{
    use HasFactory;

    public $table = 'liquidaciones_items';

    protected $fillable = [
        'liquidacion_id',
        'concepto',
        'cantidad',
        'id_servicio',
        'precio',
        'dcto',
        'iva',
        'total',
    ];

    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class, 'liquidacion_id', 'id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio', 'id');
    }
}
