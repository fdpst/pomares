<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestorDocumental extends Model
{
    use HasFactory;

    protected $table = 'gestor_documental';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'children',
        
       
      ];
}
