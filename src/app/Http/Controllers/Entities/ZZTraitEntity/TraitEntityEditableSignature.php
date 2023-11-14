<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Signature;
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
            // dump($line['id']);
            // dump($line['value']);
            if (is_null($line['id']) && is_null($line['value'])) continue;
            // dump($line);
            if (is_null($line['id'])) {
                //Insert
                if (is_null($line['signable_id'])) $line['signable_id'] = $signableId;
                $newId = Signature::create($line);
                // Log::info("Created " . $newId);
            } else { //Update or Delete
                $signature = Signature::find($line['id']);
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
