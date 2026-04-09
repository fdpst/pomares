<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnioFiscal extends Model
{
    use HasFactory;

    protected $table = 'anio_fiscal';

    protected $fillable = [
        'year',

      ];


    }