<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMetodoPago extends Model
{
    use HasFactory;

    protected $table = 'user_metodo_pago';

    protected $fillable = [
      'pago_uno',
      'pago_uno_activo',
      'pago_dos',
      'pago_dos_activo',
      'pago_tres',
      'pago_tres_activo',
      'pago_cuatro',
      'pago_cuatro_activo',
      'pago_cinco',
      'pago_cinco_activo',
      'predeterminado'
   ];

    public $timestamps = false;

    protected $casts = [
      'pago_uno_activo'    => 'boolean',
      'pago_dos_activo'    => 'boolean',
      'pago_tres_activo'   => 'boolean',
      'pago_cuatro_activo' => 'boolean',
      'pago_cinco_activo'  => 'boolean'
    ];

    protected $appends = ['nombre_metodo_pago'];

    public $nombres_metodos = [
      'pago_uno'    => 'Transferencia Bancaria',
      'pago_dos'    => 'Giro Bancario',
      'pago_tres'   => 'Efectivo',
      'pago_cuatro' => 'Paypal',
      'pago_cinco'  => 'Bizum'
    ];

    public function getNombreMetodoPagoAttribute(){
      return $this->predeterminado ? $this->nombres_metodos[$this->predeterminado] : 'N/A';
    }

    public function user(){
      return $this->belongsTo(UserMetodoPago::class, 'user_id');
    }
}
