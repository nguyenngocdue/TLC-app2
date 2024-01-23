<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Events\UpdatedEsgSheetEvent;
use App\Events\UpdatedProdSequenceEvent;
use App\Events\UpdatedQaqcChklstSheetEvent;
use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;

trait TraitUpdatedProdSequenceEvent
{
    public function emitPostUpdateEvent($id, $request)
    {
        switch ($this->type) {
            case 'prod_sequence':
                event(new UpdatedProdSequenceEvent($this->modelPath, $id));
                break;
            case 'esg_sheet':
                event(new UpdatedEsgSheetEvent($id));
                break;
            case 'qaqc_insp_chklst_sht':
                $actionButton = $request->input('actionButton');
                $mailContent = null;
                if ($actionButton) {
                    if ("SUBMIT" === $actionButton) {
                        $signatures = $request->input('signatures');
                        foreach ($signatures as $signature) {
                            if (isset($signature['signature_decision'])) {
                                $mailContent = [
                                    "user_id" => CurrentUser::id(),
                                    "signature_id" => $signature['id'],
                                    "signature_comment" => $signature['signature_comment'],
                                    "signature_decision" =>  $signature['signature_decision'],
                                    "monitors1" => Qaqc_insp_chklst_sht::find($id)->getMonitors1()->pluck('id')->toArray(),
                                ];
                                break;
                            }
                        }
                    }
                }
                event(new UpdatedQaqcChklstSheetEvent($id, $mailContent, "signature_qaqc_chklst_3rd_party"/* . "_list"*/));
                break;
            default:
                // Log::info("Updated " . $this->type);
                break;
        }
    }
}
