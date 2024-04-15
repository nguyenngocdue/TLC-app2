<?php

namespace App\Listeners;

use App\Events\UpdatedQaqcChklstEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Events\WssToastrMessageChannel;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UpdatedQaqcChklstSheetListener //implements ShouldQueue //MUST NOT QUEUE
{
    const YES = 1, NO = 2, NA_1 = 3, ON_HOLD_1 = 4, PASS = 5, FAIL = 6, NA_2 = 7, ON_HOLD_2 = 8;
    const TEXT = 1, TEXTAREA = 2, CHECKBOX = 3, RADIO = 4, DATETIME = 5, DROPDOWN = 6, SIGNATURE = 7;

    private function updateProgress($sheet)
    {
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
    }

    private function updateStatusAccordingToSignOff($sheet, $nominatedListFn)
    {
        if ($sheet->status === 'pending_audit') {
            $nominatedList = $sheet->{$nominatedListFn . "_list"}();
            $signatures = $sheet->{$nominatedListFn}()->get();
            // Log::info($signatures);
            // Log::info($signatures->pluck('id'));
            if (sizeof($signatures) == 0) {
                // Log::info("No signatures, ...");
                return;
            }

            $signature_decisions = $signatures->pluck('signature_decision');

            $allSigned = sizeof($nominatedList) == sizeof($signatures);
            $allApproved = Arr::allElementsAre($signature_decisions, 'approved');
            // Log::info("All Signed: " . $allSigned);
            // Log::info("All Approved: " . $allApproved);
            if ($allApproved && $allSigned) {
                // Log::info("Auto change status of sheet " . $sheet->id . " to audited");
                $sheet->update(['status' => 'audited']);
            } else {
                // Log::info("Do nothing for sheet #" . $sheet->id);
                // Log::info($signature_decisions);
            }
        }
    }

    public function handle(UpdatedQaqcChklstSheetEvent $event)
    {
        // if (CurrentRoute::getTypeSingular() !== 'qaqc_insp_chklst_sht') return false;
        $sheetId = $event->sheet;
        $newSignOffList = $event->newSignOffList;
        $nominatedListFn = $event->nominatedListFn;
        $sheet = Qaqc_insp_chklst_sht::find($sheetId);

        $this->updateProgress($sheet);
        $this->updateStatusAccordingToSignOff($sheet, $nominatedListFn);
        // Log::info("Elaborate updated event to Checklist...");
        event(new UpdatedQaqcChklstEvent($sheet, $newSignOffList, $nominatedListFn . "_list"));
    }
}
