<?php

namespace App\Listeners;

use App\Events\UpdateChklstProgressEvent;
use App\Events\UpdatedDocumentEvent;
use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\CurrentRoute;

class UpdateChklstSheetProgressListener
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
     * @param  \App\Events\UpdatedDocumentEvent  $event
     * @return void
     */
    public function handle(UpdatedDocumentEvent $event)
    {
        if (CurrentRoute::getTypeSingular() !== 'qaqc_insp_chklst_sht') return false;
        $sheetId = $event->currentValue['id'];
        $sheets = Qaqc_insp_chklst_sht::find($sheetId);
        $sheetLines = $sheets->getLines()->get()->toArray();

        $numLineCompletion = count($sheetLines);
        foreach (array_values($sheetLines) as $value) {
            $check1 = $value['control_type_id'] * 1;
            $check2 = $value['qaqc_insp_control_value_id'] * 1;
            $idsCompletion = [1, 3, 5, 7];
            if ($check1 * 1 === 3) continue;
            if (in_array($check1, [1, 2, 5, 7])) {
                if (is_null($value['value']) ||  (!in_array($check2, $idsCompletion) && is_null($value['value']))) {
                    $numLineCompletion -= 1;
                };
            }
            if (in_array($check1, [4, 6])) {
                if (is_null($check2) || !in_array($check2, $idsCompletion)) {
                    $numLineCompletion -= 1;
                };
            }
        }
        $percentCompletion = round(($numLineCompletion / count($sheetLines)) * 100, 2);
        // Log::info($numLineCompletion . '/' . count($sheetLines));
        $sheets->progress = $percentCompletion;
        $sheets->save();

        // $idChklst =  $event->currentValue['qaqc_insp_chklst_id'];
        $subProjectId = $sheets->getSubProject()->pluck('id')->toArray()[0];
        event(new UpdateChklstProgressEvent($subProjectId));
    }
}
