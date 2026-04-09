<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteHistorial extends Model
{
    use HasFactory;

    protected $table = 'cliente_historial';

    protected $fillable = ['cliente_id', 'fecha', 'observaciones'];

    protected $appends = ['format_fecha'];

    protected $casts = [
      'fecha' => 'date:Y-m-d'
    ];

    public function getFormatFechaAttribute(){
      return $this->fecha->format('d-m-Y');
    }

    public function cliente(){
      return $this->belongsTo(Cliente::class, 'cliente_id');
    }
}
