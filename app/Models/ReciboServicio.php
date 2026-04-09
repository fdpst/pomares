<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReciboServicio extends Model
{
    use HasFactory;

    protected $table = 'recibo_servicios';

    protected $fillable = [
        'recibo_id',
        'id_servicio',
        'descripcion',
        'cantidad',
        'precio',
        'importe',
        'user_id',
        'iva_percent',
      'importe_iva',
      'lote',
    ];

    public function Servicio()
    {
        return $this->hasOne(Servicio::class, 'id', 'id_servicio');
    }

    public function recibo()
    {
        return $this->belongsTo(Recibo::class, 'recibo_id');
    }
}
