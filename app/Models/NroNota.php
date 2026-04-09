<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NroNota extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nro_nota';

    protected $fillable = ['recibo_id', 'nro_nota',  'user_id','id_anio'];

    protected $appends = ['nro_pad'];

    protected $casts = ['created_at'  => 'date:Y-m-d'];

    public function getNroPadAttribute(){
      return str_pad($this->nro_nota, 4, '0', STR_PAD_LEFT);
    }

    public function recibo(){
      return $this->belongsTo(Recibo::class);
    }

    public function Anio(){
      return $this->hasOne(AnioFiscal::class,'id','id_anio');
    }

    public function deuda() {
      return $this->morphOne(Deuda::class, 'deuda');
    }  
}
