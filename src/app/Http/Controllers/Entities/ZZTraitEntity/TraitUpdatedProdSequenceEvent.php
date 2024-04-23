<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\SignOffSubmittedEvent;
use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Events\UpdatedUserPositionEvent;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait TraitUpdatedProdSequenceEvent
{
    private function getSubmittedMail($request, $id)
    {
        $actionButton = $request->input('actionButton');
        $mailContent = null;
        if ($actionButton) {
            if ("SUBMIT" === $actionButton) {
                $signatures = $request->input('signatures');
                foreach ($signatures as $signature) {
                    if (isset($signature['signature_decision'])) {
                        $modelPath = Str::modelPathFrom(Str::plural($this->type));
                        $monitors1 = $modelPath::find($id)->getMonitors1()->pluck('id')->toArray();
                        $mailContent = [
                            "user_id" => CurrentUser::id(),
                            "signature_id" => $signature['id'],
                            "signature_comment" => $signature['signature_comment'],
                            "signature_decision" =>  $signature['signature_decision'],
                            "monitors1" => $monitors1,
                        ];
                        break;
                    }
                }
            }
        }

        return $mailContent;
    }

    public function emitPostUpdateEvent($id, $request)
    {
        $plural = Str::plural($this->type);
        switch ($this->type) {
            case 'prod_sequence':
                event(new UpdatedProdSequenceEvent($this->modelPath, $id));
                break;
            case 'esg_sheet':
                event(new UpdatedEsgSheetEvent($id));
                break;
            case 'user_position':
                event(new UpdatedUserPositionEvent($id));
                break;
            case 'qaqc_insp_chklst_sht':
                $mailContent =  $this->getSubmittedMail($request, $id);
                if ($mailContent) event(new SignOffSubmittedEvent($mailContent, $id, $plural));
                //In case a new inspector is added, he has to be sent directly here
                //Otherwise the event will not add the current sheet into their dashboard
                $newSignOffList = $request['signature_qaqc_chklst_3rd_party_list()'];
                event(new UpdatedQaqcChklstSheetEvent($id, /*$mailContent,*/ $newSignOffList, "signature_qaqc_chklst_3rd_party"/* . "_list"*/));
                break;
            case 'qaqc_punchlist':
                $mailContent = $this->getSubmittedMail($request, $id);
                if ($mailContent) event(new SignOffSubmittedEvent($mailContent, $id,  $plural));
                break;
            default:
                // Log::info("Updated " . $this->type);
                break;
        }
    }
}
