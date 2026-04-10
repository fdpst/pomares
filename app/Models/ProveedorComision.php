<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProveedorComision extends Model
{
    protected $table = 'proveedor_comisiones';

    protected $fillable = [
        'proveedor_id',
        'servicio_id',
        'tipo',
        'valor',
        'user_id',
    ];

    protected $casts = [
        'valor' => 'float',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
