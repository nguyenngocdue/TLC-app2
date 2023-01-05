<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Events\UpdateStatusChklstRunEvent;
use App\Http\Controllers\Controller;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_control_value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

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
            $idRunSubmit = $request->input('id');
            $submitLines = $request->input('submitLines');
            if (isset($submitLines) && count($submitLines) > 0) {
                foreach ($submitLines as $value) {
                    $model = Qaqc_insp_chklst_line::find((int)$value['id']);
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
            try {
                $idNewRun = Artisan::call('ndc:cloneRun', ['--idRun' => $idRunSubmit]);
            } catch (\Throwable $th) {
                return response()->json('Artisan command call failed');
            }
            if ($idNewRun != 1) {
                event(new UpdateStatusChklstRunEvent($idNewRun));
            }
            return response()->json('Successfully');
        } catch (\Throwable $th) {
            return response()->json($th);
        }
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
