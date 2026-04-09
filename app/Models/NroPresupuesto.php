<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NroPresupuesto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nro_presupuesto';

    protected $fillable = ['recibo_id', 'nro_presupuesto',  'user_id','id_anio'];

    protected $casts = ['created_at'  => 'date:Y-m-d'];

    public function recibo(){
      return $this->belongsTo(Recibo::class, 'recibo_id');
    }
    public function Anio(){
      return $this->hasOne(AnioFiscal::class,'id','id_anio');
    }
    public function parte_trabajo(){
      return $this->hasOne(NroParteTrabajo::class, 'id');
    }
}
