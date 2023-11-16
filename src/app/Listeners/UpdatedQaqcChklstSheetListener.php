<?php

namespace App\Listeners;

use App\Events\UpdatedQaqcChklstEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdatedQaqcChklstSheetListener implements ShouldQueue
{
    const YES = 1, NO = 2, NA_1 = 3, ON_HOLD_1 = 4, PASS = 5, FAIL = 6, NA_2 = 7, ON_HOLD_2 = 8;
    const TEXT = 1, TEXTAREA = 2, CHECKBOX = 3, RADIO = 4, DATETIME = 5, DROPDOWN = 6, SIGNATURE = 7;
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
     * @param  \App\Events\UpdatedQaqcChklstSheetEvent  $event
     * @return void
     */
    public function handle(UpdatedQaqcChklstSheetEvent $event)
    {
        // if (CurrentRoute::getTypeSingular() !== 'qaqc_insp_chklst_sht') return false;
        $sheetId = $event->sheet;
        $sheet = Qaqc_insp_chklst_sht::find($sheetId);
        $sheetLines = $sheet->getLines()->get()->toArray();

        $numLineCompletion = count($sheetLines);
        $idsCompletion = [static::YES, static::NA_1, static::PASS, static::NA_2];
        foreach (array_values($sheetLines) as $value) {
            $control_type = $value['control_type_id'] * 1;
            if ($control_type === static::CHECKBOX) continue;

            $control_value = $value['qaqc_insp_control_value_id'] * 1;
            if (in_array($control_type, [static::TEXT, static::TEXTAREA, static::DATETIME, static::SIGNATURE])) {
                if (is_null($value['value']) ||  (!in_array($control_value, $idsCompletion) && is_null($value['value']))) {
                    $numLineCompletion -= 1;
                };
            }
            if (in_array($control_type, [static::RADIO, static::DROPDOWN])) {
                if (is_null($control_value) || !in_array($control_value, $idsCompletion)) {
                    $numLineCompletion -= 1;
                };
            }
        }
        $percentCompletion = round(($numLineCompletion / count($sheetLines)) * 100, 2);
        // Log::info($numLineCompletion . '/' . count($sheetLines));
        $sheet->progress = $percentCompletion;
        $sheet->save();

        // $idChklst =  $event->currentValue['qaqc_insp_chklst_id'];
        // $subProjectId = $sheet->getSubProject()->pluck('id')->toArray()[0];
        event(new UpdatedQaqcChklstEvent($sheet));
    }
}
