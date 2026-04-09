<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GestorTarea;
use App\Models\User;

class TareaPorUsuario extends Model
{
    use HasFactory;

    public function task(){
    	return $this->hasOne(GestorTarea::class, 'id', 'tarea_id');
    }

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
