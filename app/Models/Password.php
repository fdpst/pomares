<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use CustomCrypt;

class Password extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'password';

    protected $fillable = [
     'cuenta',
     'password',
     'detalle',
     'observaciones'
   ];

   protected $hidden = [
      'password',
      'real_password'
   ];

   protected $casts = [
      'created_at' => 'date:d-m-Y'
   ];

   protected $appends = [
     'real_password'
   ];

   public function getRealPasswordAttribute(){
     return CustomCrypt::Decrypt($this->password);
   }

}
