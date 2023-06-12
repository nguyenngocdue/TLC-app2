<?php

namespace App\Http\Resources;

use App\Utils\Support\Calculator;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\JsonResource;

class HrTsLineUpdateResource extends JsonResource
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
            'timesheetable_type' => $request->timesheetable_type,
            'timesheetable_id' => $request->timesheetable_id,
            'start_time' => $request->time_type ? DateTimeConcern::setTime($request->time_type, $request->start_time, $request->user_id)
                : ($request->start_time ? DateTimeConcern::formatTimestampFromJStoDB($request->start_time) : null),
            'duration_in_min' => $request->time_type ? DateTimeConcern::setDuration($request->time_type, $request->user_id) :
                DateTimeConcern::calDurationFromStartTimeAndEndTime($request->start_time, $request->end_time),
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
            'sub_project_id' => $request->sub_project_id,
            'prod_routing_id' => $request->prod_routing_id,
            'lod_id' => $request->lod_id,
            'discipline_id' => $request->discipline_id,
            'task_id' => $request->task_id,
            'sub_task_id' => $request->sub_task_id,
            'work_mode_id' => $request->work_mode_id,
            'remark' => $request->remark,
            'owner_id' => $request->owner_id,
            'status' => $request->status,
        ];
    }
}
