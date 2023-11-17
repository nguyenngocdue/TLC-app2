<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

// use App\Events\InspChklstEvent;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait TraitEventInspChklst
{
    private function fireEventInspChklst(Request $request, $id)
    {
        $signOffIds = $request->input('getMonitors1()');
        if ($this->modelPath === Qaqc_insp_chklst_sht::class && $signOffIds) {
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
                $currentUserId = (string)Auth::id();
                $ids = array_unique(array_merge([$currentUserId], $commentOwnerIds));
                if (!empty(array_intersect($ids, $signOffIds))) {
                    $data['owner_id'] = $currentUserId;
                    if (!empty($failIds)) {
                        $qaqcInspChklstLineData = Qaqc_insp_chklst_line::whereIn('id', $failIds)->select('name', 'qaqc_insp_control_value_id')->get()->toArray();
                        $data['no_or_fail'] = $qaqcInspChklstLineData;
                    }
                    if (!empty($dataCommentCreate)) {
                        $dataCommentCreate = array_map(function ($item) {
                            $result = ($item['commentable_type'])::findOrFail($item['commentable_id'])->toArray()['name'];
                            $item['content_line_name'] = $result;
                            return $item;
                        }, $dataCommentCreate);
                        $data['comment'] = $dataCommentCreate;
                    }
                    // event(new InspChklstEvent($data, $id, $this->type));
                }
            }
        }
    }
}
