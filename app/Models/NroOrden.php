<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NroOrden extends Model
{
    use HasFactory;

    protected $table = 'nro_orden';

  	protected $fillable = ['recibo_id', 'nro_orden',  'user_id'];

  	protected $casts = ['created_at'  => 'date:Y-m-d'];

  	public function recibo(){
  		return $this->belongsTo(Recibo::class);
  	}
}
