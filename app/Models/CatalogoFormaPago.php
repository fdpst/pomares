<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoFormaPago extends Model
{
    protected $table = 'catalogo_formas_pago';

    protected $fillable = [
        'user_id',
        'descripcion',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proveedores(): HasMany
    {
        return $this->hasMany(Proveedor::class, 'catalogo_forma_pago_id');
    }
}
