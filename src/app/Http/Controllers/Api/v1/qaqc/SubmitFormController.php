<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Http\Controllers\Controller;
use App\Models\Prod_run_line;
use App\Models\Prod_run;
use App\Models\Prod_user_run;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_control_value;
use App\Models\User;
use App\Utils\System\Api\ResponseObject;
use App\Utils\System\GetSetCookie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubmitFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        try {
            $submitLines = $request->input('submitLines');
            if (isset($submitLines) && count($submitLines) > 0) {
                foreach ($submitLines as $value) {
                    $model = Qaqc_insp_chklst_line::find($value['id']);
                    $model->update([
                        'value' => $value['value'],
                        'qaqc_insp_control_value_id' => $value['controlValueId'],
                    ]);
                    if ($value['valueCheckbox']) {
                        $arrayCheckBoxIds = array_keys(array_filter($value['valueCheckbox']), fn ($item) => $item == 'true');
                        $filedName = $this->getFunctionOracyCheckbox($value['controlValueId']);
                        $this->syncCheckBoxManyToMany($model, $filedName, $arrayCheckBoxIds);
                    }
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response()->json('oke');
    }

    private function getFunctionOracyCheckbox($controlValueId, $controlGroupId = 1)
    {
        $modelControlValue = Qaqc_insp_control_value::find($controlValueId);
        $nameControlValue = $modelControlValue->name;
        if ($modelControlValue->getControlGroup->id == $controlGroupId) {
            $nameControlValue == 'No' ?  $filedName = 'getNoOfYesNo' : 'getOnHoldOfYesNo';
        } else {
            $nameControlValue == 'Fail' ?  $filedName = 'getFailedOfPassFail' : 'getOnHoldOfPassFail';
        }
        return $filedName;
    }
    private function syncCheckBoxManyToMany($model, $filedName, $arrayIds)
    {
        $model->syncCheck($filedName, $this->getClassModelByFiledName($model, $filedName), $arrayIds);
    }
    private function getClassModelByFiledName($model, $filedName)
    {
        return $model->oracyParams[$filedName . '()'][1];
    }
}
