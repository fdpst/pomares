<?php

use App\Enums\ParamSystemEnum;
use App\Enums\ParamSystemTypeEnum;
use App\Models\SystemParam;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('system_params')) {
            return;
        }

        $options = [
            ['label' => 'Clásico', 'value' => 'classic'],
            ['label' => 'Simple', 'value' => 'simple'],
        ];

        $users = User::all();
        foreach ($users as $user) {
            SystemParam::firstOrCreate(
                [
                    'param' => ParamSystemEnum::ALBARAN_TEMPLATE->value,
                    'business_id' => $user->id,
                ],
                [
                    'label' => 'Diseño de albarán',
                    'value' => 'classic',
                    'type' => ParamSystemTypeEnum::SELECTABLE,
                    'options' => $options,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('system_params')) {
            return;
        }

        SystemParam::where('param', ParamSystemEnum::ALBARAN_TEMPLATE->value)->delete();
    }
};

