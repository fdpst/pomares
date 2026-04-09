<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailPredefinido extends Model
{
    use HasFactory;
    protected $table= 'mail_predefinido';
    protected $fillable = ['body','tipo'];
    
}
