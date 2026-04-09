<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'cliente';

    protected $fillable = [
        'nro_cliente',
        'nombre',
        'nombre_comercial',
        'dni',
        'email',
        'telefono',
        'direccion',
        'codigo_postal',
        'provincia_id',
        // START cambios para cientes con Pais
        'pais_id',
        // END cambios para cientes con Pais
        'localidad',
        'user_id',
        'observaciones',
        'contacto_nombre',
        'contacto_telefono',
        'cuenta',
        'banco',
        'forma_pago_id',
        'activo',
        'fecha_alta',
        'id_cuenta_contable',
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    // START cambios para cientes con Pais
    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }
    // END cambios para cientes con Pais

    public function historial()
    {
        return $this->hasMany(ClienteHistorial::class)->orderBy('fecha', 'DESC');
    }

    public function cuentaContable()
    {
        return $this->hasOne(CuentaContable::class, 'id', 'id_cuenta_contable');
    }
}
