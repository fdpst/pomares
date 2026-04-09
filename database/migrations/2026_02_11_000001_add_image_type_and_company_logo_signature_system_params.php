<?php

use App\Enums\ParamSystemEnum;
use App\Enums\ParamSystemTypeEnum;
use App\Models\SystemParam;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade tipo IMAGE al enum y crea parámetros company_logo y company_signature por negocio.
     */
    public function up()
    {
        if (!Schema::hasTable('system_params')) {
            return;
        }

        $enumValues = implode(',', array_map(fn($v) => "'" . $v . "'", ParamSystemTypeEnum::values()));
        DB::statement("ALTER TABLE system_params MODIFY COLUMN type ENUM({$enumValues}) DEFAULT 'TEXT'");

        $users = User::all();
        foreach ($users as $user) {
            SystemParam::firstOrCreate(
                [
                    'param' => ParamSystemEnum::COMPANY_LOGO->value,
                    'business_id' => $user->id,
                ],
                [
                    'label' => 'Logo de la empresa',
                    'value' => '',
                    'type' => ParamSystemTypeEnum::IMAGE,
                ]
            );
            SystemParam::firstOrCreate(
                [
                    'param' => ParamSystemEnum::COMPANY_SIGNATURE->value,
                    'business_id' => $user->id,
                ],
                [
                    'label' => 'Firma para facturas',
                    'value' => '',
                    'type' => ParamSystemTypeEnum::IMAGE,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (!Schema::hasTable('system_params')) {
            return;
        }

        SystemParam::where('param', ParamSystemEnum::COMPANY_LOGO->value)->delete();
        SystemParam::where('param', ParamSystemEnum::COMPANY_SIGNATURE->value)->delete();

        $withoutImage = ['TEXT', 'NUMBER', 'BOOLEAN', 'SELECTABLE', 'TEXTAREA'];
        $enumValues = implode(',', array_map(fn($v) => "'" . $v . "'", $withoutImage));
        DB::statement("ALTER TABLE system_params MODIFY COLUMN type ENUM({$enumValues}) DEFAULT 'TEXT'");
    }
};
