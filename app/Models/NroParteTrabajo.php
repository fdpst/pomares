<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NroParteTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nro_parte_trabajo';

    protected $fillable = ['recibo_id', 'nro_presupuesto_id', 'nro_parte_trabajo', 'user_id'];

    protected $casts = ['created_at'  => 'date:Y-m-d'];

    public function recibo(){
      return $this->belongsTo(Recibo::class);
    }

    public function presupuesto(){
      return $this->belongsTo(NroPresupuesto::class, 'nro_presupuesto_id');
    }

    public function setReciboIdAttribute($value){
      $contador = $this->withTrashed()->get()->count();
      $this->attributes['nro_parte_trabajo'] = $contador + 1;
      $this->attributes['recibo_id'] = $value;
    }
}
