<?php

namespace App\Http\Resources;

use App\Utils\Constant;
use App\Utils\Support\Calculator;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class HrTsLineStoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            // 'timesheetable_type' => $request->timesheetable_type,
            // 'timesheetable_id' => $request->timesheetable_id,
            'hr_timesheet_officer_id' => $request->hr_timesheet_officer_id,
            'start_time' => $request->date_time ? DateTimeConcern::formatTimestampFromJStoDB($request->date_time) : null,
            'duration_in_min' => DateTimeConcern::isFormatJsDateTime($request->date_time) ? 60 : null,
            'project_id' => $request->project_id,
            'sub_project_id' => $request->sub_project_id,
            // 'prod_routing_id' => $request->prod_routing_id,
            'lod_id' => $request->lod_id,
            'discipline_id' => $request->discipline_id,
            'task_id' => $request->task_id,
            'sub_task_id' => $request->sub_task_id,
            'work_mode_id' => $request->work_mode_id ?? 2,
            'remark' => $request->remark,
            'user_id' => $request->user_id,
            'owner_id' => $request->user_id,
            // 'status' => $request->status ?? 'new',
        ];
    }
}
