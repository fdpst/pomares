<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaCuentaContable extends Model
{
    use HasFactory;

    public $table = 'categoria_cuenta_contable';

    protected $fillable = ['cuenta', 'denominacion'];
}
