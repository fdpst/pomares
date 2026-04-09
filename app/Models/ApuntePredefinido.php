<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApuntePredefinido extends Model
{
    use HasFactory;

    public $table = 'apunte_predefinido';

    protected $fillable = [ 'descripcion' ];
}
