<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbaranEnviadoItemAgregado extends Model
{
    use HasFactory;
    protected $table = 'albaran_enviado_item_agregados';
    protected $fillable =
    [
        'albaran_enviado_id',
        'descripcion',	
        'cantidad',
        'precio',
        'importe'
    ];
}