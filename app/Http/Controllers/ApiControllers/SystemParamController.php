<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemParam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\GestorHelper;
use App\Enums\ParamSystemEnum;
use App\Enums\ParamSystemTypeEnum;

class SystemParamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            return response()->json(SystemParam::where(function ($query) use ($request) {
                if ($request->business_id && $request->business_id != "") {
                    $query->where("business_id", $request->business_id);
                } else {
                    throw new \Exception("not allowed", 403);
                }
            })->get(), 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            return response()->json(SystemParam::create($request->all()), 201);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return response()->json(SystemParam::findOrFail($id));
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $param = SystemParam::findOrFail($id);
            $param->fill($request->all());
            $param->save();
            return response()->json($param, 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $param = SystemParam::findOrFail($id);
            $param->delete();
            return response()->json([
                "success" => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function saveBulk(Request $request)
    {
        try {
            DB::beginTransaction();
            $payload = $request->all();
            foreach ($payload as $param) {
                $save = SystemParam::firstOrNew(["id" => $param["id"] ?? null, "business_id" => $param["business_id"]]);
                $save->fill($param);
                $save->value = $save->id ? $save->value : $param["value"];
                $save->business_id = $save->id ? $save->business_id : $param["business_id"];
                $save->save();
            }
            DB::commit();
            return response()->json([
                "success" => true
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function getByParamName(Request $request, string $name)
    {
        try {
            $businessId = GestorHelper::getUserId($request, Auth::id());

            if (!$businessId) {
                throw new \Exception("not allowed", 403);
            }

            $definition = $this->definitionFor($name);

            $param = SystemParam::firstOrCreate(
                [
                    "param" => $definition["param"],
                    "business_id" => $businessId,
                ],
                [
                    "label" => $definition["label"],
                    "type" => $definition["type"],
                    "value" => $definition["value"],
                ]
            );

            return response()->json($param, 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ], 500);
        }
    }

    protected function definitionFor(string $name): array
    {
        return match ($name) {
            ParamSystemEnum::TEXT_EMAIL_PAY_REMINDER->value => [
                "param" => ParamSystemEnum::TEXT_EMAIL_PAY_REMINDER->value,
                "label" => "Texto email recordatorio de pago",
                "type" => ParamSystemTypeEnum::TEXTAREA->value,
                "value" => "",
            ],
            ParamSystemEnum::INVOICE_FOOTER->value => [
                "param" => ParamSystemEnum::INVOICE_FOOTER->value,
                "label" => "Pie de factura",
                "type" => ParamSystemTypeEnum::TEXT->value,
                "value" => "",
            ],
            ParamSystemEnum::ENABLE_BATCH->value => [
                "param" => ParamSystemEnum::ENABLE_BATCH->value,
                "label" => "Activar lote",
                "type" => ParamSystemTypeEnum::BOOLEAN->value,
                "value" => false,
            ],
            default => [
                "param" => $name,
                "label" => ucfirst(str_replace("_", " ", $name)),
                "type" => ParamSystemTypeEnum::TEXT->value,
                "value" => "",
            ],
        };
    }
}
