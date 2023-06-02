<?php

namespace App\Http\Resources;

use App\Utils\Support\Calculator;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HrTsLineStoreResource extends JsonResource
{
    use Calculator;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'timesheetable_type' => $request->timesheetable_type,
            'timesheetable_id' => $request->timesheetable_id,
            'start_time' => $this->formatTimestampFromJStoDB($request->date_time),
            'duration_in_min' => $this->isFormatJsDateTime($request->date_time) ? 60 : null,
            'user_id' => CurrentUser::id() ?? 1,
            'project_id' => $request->project_id,
            'sub_project_id' => $request->sub_project_id,
            'prod_routing_id' => $request->prod_routing_id,
            'lod_id' => $request->lod_id,
            'discipline_id' => $request->discipline_id,
            'task_id' => $request->task_id,
            'sub_task_id' => $request->sub_task_id,
            'work_mode_id' => $request->work_mode_id,
            'remark' => $request->remark,
            'owner_id' => CurrentUser::id() ?? 1,
            'status' => $request->status ?? 'new',
        ];
    }
}
