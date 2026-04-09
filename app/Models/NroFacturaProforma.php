<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NroFacturaProforma extends Model
{
    protected $table = 'nro_factura_proforma';

  	protected $fillable = ['user_id', 'recibo_id', 'nro_factura_prof','id_anio'];

  	protected $casts = ['created_at'  => 'date:Y-m-d'];

  	public function recibo(){
  		return $this->belongsTo(Recibo::class);
  	}

	public function Anio()
	{
		return $this->belongsTo(AnioFiscal::class, 'id_anio');
	}
	
    // public function deuda() {
    //    return $this->morphOne(Deuda::class, 'deuda');
    // }

  	/*public function setReciboIdAttribute($value){
  		$contador = $this->get()->count();
  		$this->attributes['nro_factura'] = $contador + 1;
  		$this->attributes['recibo_id'] = $value;
  	}*/
}
