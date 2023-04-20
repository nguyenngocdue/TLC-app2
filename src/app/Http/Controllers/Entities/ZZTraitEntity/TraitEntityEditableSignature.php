<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Qaqc_insp_chklst_sht_sig;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitEntityEditableSignature
{
    function processSignatures(Request $request, $signableId = null)
    {
        $signatures = $request->input('signatures');
        if (is_null($signatures)) return;

        $currentUser = CurrentUser::get();

        foreach ($signatures as $line) {
            if (is_null($line['id']) && is_null($line['value'])) continue;
            // dump($line);
            if (is_null($line['id'])) {
                //Insert
                if (is_null($line['qaqc_insp_chklst_sht_id'])) {
                    $line['qaqc_insp_chklst_sht_id'] = $signableId;
                }
                $newId = Qaqc_insp_chklst_sht_sig::create($line);
                // Log::info("Created " . $newId);
            } else { //Update or Delete
                $signature = Qaqc_insp_chklst_sht_sig::find($line['id']);
                // dump($line);
                if ($signature && $signature['owner_id'] == $currentUser->id) {
                    if (is_null($line['value'])) {
                        // Log::info("Deleted " . $line['id']);
                        $signature->delete();
                    } else {
                        // Log::info("Updated " . $line['id']);
                        $signature->update($line);
                    }
                }
            }
        }
        // dump($signatures);
        // dd($signatures);
    }
}
