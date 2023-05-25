<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitEventInspChklst
{
    private function eventInspChklst(Request $request, $id)
    {
        $signOffIds = $request->input('getSignOff()');
        if ($this->data === Qaqc_insp_chklst_sht::class && $signOffIds) {
            $dataTable = $request->input('table01');
            $failIds = array_filter($dataTable['control_fail_current_session_ids'], function ($item) {
                return $item;
            });
            $dataComment = $request->input('comments');
            $dataCommentCreate = array_filter($dataComment, function ($item) {
                return (!$item['id'] && $item['content']);
            });
            $commentOwnerIds = array_column($dataCommentCreate, 'owner_id');
            if (!empty($failIds) || !empty($commentOwnerIds)) {
                $ids = array_unique(array_merge([(string)Auth::id()], $commentOwnerIds));
                if (!empty(array_intersect($ids, $signOffIds))) {
                    $qaqcInspChklstLine = Qaqc_insp_chklst_line::whereIn('id', $failIds)->get();
                    dd(123);
                }
            }
        }
    }
}
