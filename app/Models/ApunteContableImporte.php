<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApunteContableImporte extends Model
{
    use HasFactory;
    protected $table ='apunte_contable_importe';
    protected $fillable = ['id_apunte','iva','subtotal','importe_iva'];
    public function Apunte(){
        return $this->hasOne(ApunteContable::class,'id','id_apunte');
    }
}
