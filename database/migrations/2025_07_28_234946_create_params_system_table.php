<?php

use App\Enums\ParamSystemTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        try {
            DB::beginTransaction();
            Schema::create('system_params', function (Blueprint $table) {
                $table->id();
                $table->string("param");
                $table->longText("value")->nullable();
                $table->string("label");
                $table->json("options")->nullable();
                $table->foreignId("business_id")->nullable()->constrained("users");
                $table->timestamps();
            });
            $options = implode(", ", array_map(function ($item) {
                return "'" . $item . "'";
            }, ParamSystemTypeEnum::values()));
            $default = ParamSystemTypeEnum::TEXT->value;
            DB::statement("ALTER TABLE system_params ADD COLUMN type ENUM($options) DEFAULT '$default'");
            DB::commit();
        } catch (\Exception $e) {
            Log::error("No se pudo ejecutar la migracion 'system_params' debido a: " . $e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('system_params');
    }
};
