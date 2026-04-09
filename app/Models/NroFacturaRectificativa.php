<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NroFacturaRectificativa extends Model
{
    protected $table = 'nro_factura_rectificativas';

  	protected $fillable = ['recibo_id', 'nro_factura',  'user_id','id_anio'];

  	protected $casts = ['created_at'  => 'date:Y-m-d'];

  	public function recibo(){
  		return $this->belongsTo(Recibo::class);
  	}
	public function Anio(){
		return $this->hasOne(AnioFiscal::class,'id','id_anio');
	}
    public function deuda() {
       return $this->morphOne(Deuda::class, 'deuda');
    }

  	/*public function setReciboIdAttribute($value){
  		$contador = $this->get()->count();
  		$this->attributes['nro_factura'] = $contador + 1;
  		$this->attributes['recibo_id'] = $value;
  	}*/
}
