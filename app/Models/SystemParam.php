<?php

namespace App\Models;

use App\Enums\ParamSystemTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemParam extends Model
{
    use HasFactory;

    protected $table = "system_params";
    protected $fillable = [
        "label",
        "param",
        "value",
        "type",
        "options",
        "business_id"
    ];

    protected $casts = [
        "type" => ParamSystemTypeEnum::class,
        "options" => "array"
    ];

    public function business()
    {
        return $this->belongsTo(User::class, "business_id", "id");
    }
}
