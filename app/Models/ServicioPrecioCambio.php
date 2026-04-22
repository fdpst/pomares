<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicioPrecioCambio extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'servicio_precio_cambios';

    protected $fillable = [
        'servicio_id',
        'user_id',
        'precio_anterior',
        'precio_nuevo',
    ];

    protected $casts = [
        'precio_anterior' => 'float',
        'precio_nuevo' => 'float',
        'created_at' => 'datetime',
    ];

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
