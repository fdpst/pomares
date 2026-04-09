<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordToken extends Model
{
    use HasFactory;

    protected $table = 'password_token';

    protected $fillable = [
      'user_id',
      'tipo',
      'token'
    ];

    public function user(){
      return $this->belongsTo(User::class, 'user_id');
    }
}
