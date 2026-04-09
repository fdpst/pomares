<?php

namespace Database\Seeders;

use App\Enums\ParamSystemEnum;
use App\Enums\ParamSystemTypeEnum;
use App\Models\SystemParam;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemParam::insert([
            "label" => "Texto email recordatorio de pago",
            "param" => ParamSystemEnum::TEXT_EMAIL_PAY_REMINDER->value,
            "value" => "",
            "type" => ParamSystemTypeEnum::TEXTAREA->value,
            "created_at" => Carbon::now()
        ]);
    }
}
