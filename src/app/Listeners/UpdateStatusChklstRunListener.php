<?php

namespace App\Listeners;

use App\Events\UpdateStatusChklstRunEvent;
use App\Models\Qaqc_insp_chklst_run;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class UpdateStatusChklstRunListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UpdateStatusChklstRunEvent  $event
     * @return void
     */
    public function handle(UpdateStatusChklstRunEvent $event)
    {
        $arrayIdFail = [2, 6];
        $arrayIdOnHold = [4, 8];
        $model = Qaqc_insp_chklst_run::find($event->idChklstRun);
        $qaqcInspChklstLines = $model->getLines;
        $arrayControlValueId = [];
        $totalLines = count($qaqcInspChklstLines);
        $count = 0;
        foreach ($qaqcInspChklstLines as $qaqcInspChklstLine) {
            array_push($arrayControlValueId, $qaqcInspChklstLine['qaqc_insp_control_value_id']);
            if ($qaqcInspChklstLine['qaqc_insp_control_value_id'] != null || $qaqcInspChklstLine['value'] != null) {
                $count++;
            }
        }
        if (Arr::containsEach($arrayIdFail, $arrayControlValueId)) {
            $status = 'fail';
        } else if (
            !Arr::containsEach($arrayIdFail, $arrayControlValueId) &&
            Arr::containsEach($arrayIdOnHold, $arrayControlValueId)
        ) {
            $status = 'on_hold';
        } else {
            $status = 'pass';
        }
        $progress = ($count / $totalLines) * 100;
        $model->update([
            'status' => $status,
            'progress' => $progress
        ]);
    }
}
