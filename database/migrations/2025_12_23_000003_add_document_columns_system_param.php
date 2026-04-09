<?php

use App\Enums\ParamSystemEnum;
use App\Enums\ParamSystemTypeEnum;
use App\Helpers\DocumentColumnsHelper;
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

        $users = User::all();
        foreach ($users as $user) {
            SystemParam::firstOrCreate(
                [
                    'param' => ParamSystemEnum::DOCUMENT_COLUMNS->value,
                    'business_id' => $user->id,
                ],
                [
                    'label' => 'Columnas de documentos',
                    'value' => json_encode(DocumentColumnsHelper::defaults()),
                    'type' => ParamSystemTypeEnum::TEXTAREA,
                    'options' => [],
                ],
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

        SystemParam::where('param', ParamSystemEnum::DOCUMENT_COLUMNS->value)->delete();
    }
};

