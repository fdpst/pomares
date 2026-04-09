<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GestorTarea;
use App\Models\DraggableList;

class TareaPorDragg extends Model
{
    use HasFactory;


    public function task(){
    	return $this->hasOne(GestorTarea::class, 'id', 'tarea_id');
    }

    public function drag(){
    	return $this->belongsTo(DraggableList::class);
    }
}
