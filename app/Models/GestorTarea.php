<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TareaPorUsuario;

class GestorTarea extends Model
{
    use HasFactory;


    // public function taskUser(){
    // 	return $this->hasMany(TareaPorUsuario::class);
    // }
}
