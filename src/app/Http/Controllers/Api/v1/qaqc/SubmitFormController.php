<?php

namespace App\Http\Controllers\Api\v1\qaqc;

use App\Console\Commands\Traits\CloneRunTrait;
use App\Events\UpdateStatusChklstRunEvent;
use App\Http\Controllers\Controller;
use App\Models\Qaqc_insp_chklst_line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SubmitFormController extends Controller
{
    use CloneRunTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
        try {
            $idRunSubmit = $request->input('id');
            $ownerId = $request->input('ownerId');
            $submitLines = $request->input('submitLines');
            $arrayValueCheckbox = [];
            $arrayControlValueId = [];
            if (isset($submitLines) && count($submitLines) > 0) {
                foreach ($submitLines as $value) {
                    $model = Qaqc_insp_chklst_line::find((int)$value['id']);
                    $model->update([
                        'value' => $value['value'],
                        'qaqc_insp_control_value_id' => $value['controlValueId'],
                    ]);
                    array_push($arrayValueCheckbox, $value['valueCheckbox']);
                    array_push($arrayControlValueId, $value['controlValueId']);
                    if ($value['valueCheckbox']) {
                        $arrayCheckBoxIds = array_keys(array_filter($value['valueCheckbox']), fn ($item) => $item == 'true');
                        $filedName = $this->getFunctionOracyCheckbox($value['controlValueId']);
                        $this->syncCheckBoxManyToMany($model, $filedName, $arrayCheckBoxIds);
                    }
                }
            }
            $command = Artisan::call('ndc:cloneRun', [
                '--idRun' => $idRunSubmit, '--ownerId' => $ownerId,
                '--arrayCheckBox' => $arrayValueCheckbox, '--arrayControlValueId' => $arrayControlValueId
            ]);
            if ($command == 1) return response()->json('Artisan command call failed');
            event(new UpdateStatusChklstRunEvent($idRunSubmit));
            return response()->json('Successfully');
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
