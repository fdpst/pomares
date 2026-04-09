<?php

namespace App\Models\Albaranes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnioFiscal;
use App\Models\Cliente;
use App\Models\AlbaranEnviadoItemAgregado;

class AlbaranesEnviado extends Model
{
    use HasFactory;

    protected $table = 'albaranes_enviados';
    protected $appends=['nro'];
    protected $fillable = [
      'user_id',
      'cliente_id',
      'fecha',
      'descripcion',
      'cantidad',
      'precio',
      'importe',
      'nro_factura',
      'url',
      'json_recibido',
      'contabilizado',
      'id_anio',
      'unificado',
      'albaran_unificado_id',
      'template',
    //   'cobrado',
    ];
    public function  getNroAttribute(){
        return substr($this->Anio->year,2). str_pad($this->nro_factura, 4, '0', STR_PAD_LEFT);
    }
    public function Anio(){
		return $this->hasOne(AnioFiscal::class,'id','id_anio');
	}
     public function itemAlbaran(){
        return $this->hasMany(AlbaranEnviadoItemAgregado::class, 'albaran_enviado_id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

}
