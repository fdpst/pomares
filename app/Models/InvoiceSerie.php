<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSerie extends Model
{
    use HasFactory;

    protected $fillable = [
        "serie",
        "user_id"
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (self::where('user_id', $model->user_id)
                ->where('serie', $model->serie)
                ->exists()
            ) {
                throw new \Exception('La serie que intentas crear ya existe.');
            }
        });

        /*static::updating(function ($model) {
            if (self::where('user_id', $model->user_id)
                ->where('serie', $model->serie)
                ->exists()
            ) {
                throw new \Exception('La serie que intentas ya existe');
            }
        });*/
    }
}
