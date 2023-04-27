<?php

namespace App\Console\Commands\Traits;

use App\Models\Qaqc_insp_chklst_run_line;
use App\Models\Qaqc_insp_control_value;
use Illuminate\Support\Facades\Log;

trait CloneRunTrait
{
    /**
     * cloneSheet
     *
     * @param  mixed $modelRun Model Run need clone
     * @param  mixed $idSheet 
     * @return void
     */
    public function cloneRunLine($modelRun, $newModelRun = null, $idRun = null, $arrayValueCheckbox = null, $arrayControlValueId = null,)
    {
        $qaqcInspTmplLines = $modelRun->getLines;
        if (count($qaqcInspTmplLines) > 0) {
            foreach ($qaqcInspTmplLines as $key => $qaqcInspTmplLine) {
                $model = Qaqc_insp_chklst_run_line::create([
                    'name' => $qaqcInspTmplLine->name,
                    'description' => $qaqcInspTmplLine->description,
                    'control_type_id' => $qaqcInspTmplLine->control_type_id,
                    'qaqc_insp_chklst_run_id' => $idRun ? $idRun : $newModelRun->id,
                    'qaqc_insp_group_id' => $qaqcInspTmplLine->qaqc_insp_group_id,
                    'qaqc_insp_control_value_id' => $newModelRun ? $qaqcInspTmplLine->qaqc_insp_control_value_id : null,
                    'qaqc_insp_control_group_id' => $qaqcInspTmplLine->qaqc_insp_control_group_id,
                ]);
                if ($arrayValueCheckbox) {
                    if ($arrayValueCheckbox[$key]) {
                        $arrayCheckBoxIds = array_keys(array_filter($arrayValueCheckbox[$key]), fn ($item) => $item == 'true');
                        $filedName = $this->getFunctionOracyCheckbox($arrayControlValueId[$key]);
                        $a = $this->syncCheckBoxManyToMany($model, $filedName, $arrayCheckBoxIds);
                    }
                }
            }
        }
    }
    public function getFunctionOracyCheckbox($controlValueId, $controlGroupId = 1)
    {
        $modelControlValue = Qaqc_insp_control_value::find($controlValueId);
        $nameControlValue = $modelControlValue->name;
        if ($modelControlValue->getControlGroup->id == $controlGroupId) {
            $filedName = $nameControlValue == 'No' ? 'getNoOfYesNo' : 'getOnHoldOfYesNo';
        } else {
            $filedName = $nameControlValue == 'Fail' ?  'getFailedOfPassFail' : 'getOnHoldOfPassFail';
        }
        return $filedName;
    }
    public function syncCheckBoxManyToMany($model, $filedName, $arrayIds)
    {
        $model->syncCheck($filedName, $this->getClassModelByFiledName($model, $filedName), $arrayIds);
    }
    public function getClassModelByFiledName($model, $filedName)
    {
        return $model->oracyParams[$filedName . '()'][1];
    }
}
