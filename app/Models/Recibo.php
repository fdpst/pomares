<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class Recibo extends Model
{
  use HasFactory;

  protected $table = 'recibo';

  protected $fillable = [
    'cliente_id',
    'fecha',
    'fecha_tope',
    'fecha_pago',
    'sub_total',
    'iva',
    'tipo_iva',
    'tipo_descuento',
    'descuento',
    'total_descuento',
    'porcentaje_descuento',
    'total',
    'presupuesto_url',
    'factura_url',
    'nota_url',
    'orden_url',
    'has_iva',
    'user_id',
    'observaciones',
    'lote',
    'metodo_pago',
    'detalle_pago',
    'enviado',
    'pagado',
    'recurrente',
    'serie_id',
    'qr_code_electronic_invoicing_string',
    'fecha_recurrente',
    'unificado',
    'recibo_unificado_id',
  ];

  /**
   * Prevenir que 'servicios' se guarde como atributo
   * ya que es una relación, no un campo de la tabla
   */
  protected $guarded = ['servicios'];

  protected $appends = ['nro'];
  public function getNroAttribute()
  {
    if ($this->nro_factura) {
      return substr($this->nro_factura->Anio->year, 2) .
        str_pad($this->nro_factura?->nro_factura, 4, '0', STR_PAD_LEFT);
    } else if ($this->nro_factura_rectificativa) {
      return substr($this->nro_factura_rectificativa->Anio->year, 2) .
        str_pad($this->nro_factura?->nro_factura, 4, '0', STR_PAD_LEFT);
    }
    return '';
  }
  protected $casts = [
    'has_iva' => 'boolean',
    'enviado' => 'boolean',
    'pagado' => 'boolean',
    'recurrente' => 'boolean',
    'unificado' => 'boolean'
  ];

  protected $dates = [
    'fecha'
  ];

  public function setActiveAttribute($iva)
  {
    $this->attributes['has_iva'] = ($iva == 'true') ? 1 : 0;
  }

  public function cliente()
  {
    return $this->belongsTo(Cliente::class, 'cliente_id');
  }

  public function servicios()
  {
    return $this->hasMany(ReciboServicio::class);
  }

  public function nro_factura()
  {
    return $this->hasOne(NroFactura::class);
  }
  public function nro_factura_rectificativa()
  {
    return $this->hasOne(NroFacturaRectificativa::class);
  }
  public function nro_orden()
  {
    return $this->hasOne(NroOrden::class);
  }

  public function nro_factura_prof()
  {
    return $this->hasOne(NroFacturaProforma::class);
  }

  public function nro_nota()
  {
    return $this->hasOne(NroNota::class);
  }

  public function nro_presupuesto()
  {
    return $this->hasOne(NroPresupuesto::class);
  }

  public function nro_parte_trabajo()
  {
    return $this->hasOne(NroParteTrabajo::class);
  }

  public function setFechaAttribute($fecha)
  {
    $this->attributes['fecha'] = Carbon::createFromDate($fecha)->format('Y-m-d');
  }

  public function getFechaAttribute()
  {
    return Carbon::createFromDate($this->attributes['fecha'])->format('Y-m-d');
  }

  /**
   * Prevenir que 'servicios' se guarde como atributo
   * ya que es una relación, no un campo de la tabla
   */
  public function setServiciosAttribute($value)
  {
    // Ignorar cualquier intento de asignar servicios como atributo
    // Los servicios se guardan en la tabla recibo_servicios mediante la relación
    // No hacer nada, simplemente ignorar la asignación
  }

  /**
   * Boot del modelo - eventos
   */
  protected static function boot()
  {
    parent::boot();

    // Antes de guardar, asegurar que 'servicios' no esté en los atributos
    static::saving(function ($recibo) {
      if (isset($recibo->attributes['servicios'])) {
        unset($recibo->attributes['servicios']);
      }
    });
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'id');
  }
  /**
   * Duplicada debido a que desconozco si la funcion "user()"  funciona como relacion para 
   * otros fragmentos de codigo
   */
  public function user2()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function serie()
  {
    return $this->hasOne(InvoiceSerie::class, 'id', 'serie_id');
  }

  public function reciboUnificado()
  {
    return $this->belongsTo(Recibo::class, 'recibo_unificado_id');
  }
}
