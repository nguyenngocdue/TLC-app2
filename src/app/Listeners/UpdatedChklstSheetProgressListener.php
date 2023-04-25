<?php

namespace App\Listeners;

use App\Events\UpdateChklstProgressEvent;
use App\Events\UpdatedDocumentEvent;
use App\Events\UpdatedProgressEvent;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdatedChklstSheetProgressListener
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
        $sheetId = $event->currentValue['id'];
        $sheets = Qaqc_insp_chklst_sht::find($sheetId);
        $sheetLines = $sheets->getLines()->get()->ToArray();

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
        Log::info($numLineCompletion . '/' . count($sheetLines));
        $sheets->progress = $percentCompletion;
        $sheets->save();

        // dd($event);
        $idChklst =  $event->currentValue['qaqc_insp_chklst_id'];
        event(new UpdateChklstProgressEvent($idChklst, $numLineCompletion));
    }
}
