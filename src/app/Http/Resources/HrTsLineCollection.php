<?php

namespace App\Http\Resources;

use App\Models\Pj_task;
use App\Models\Public_holiday;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HrTsLineCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                return [
                    'title' => Calendar::renderTitle($item),
                    'title_default' => $item->task_id ? Pj_task::findOrFail($item->task_id)->name : '',
                    'tag_sub_project' => Calendar::renderTagSubProject($item) ?? '',
                    'name_project' => Calendar::renderNameProject($item) ?? '',
                    'start' => $item->start_time ? DateTimeConcern::formatTimestampFromDBtoJS($item->start_time) : DateTimeConcern::formatTimestampFromDBtoJSForPH($item),
                    'end' => $item->start_time ? DateTimeConcern::calTimestampEndFromStartTimeAndDuration($item->start_time, $item->duration_in_min) : DateTimeConcern::calTimestampEndFromStartTimeAndDurationForPH($item),
                    // 'allDay' => ($item->duration_in_min >= Constant::TIME_DEFAULT_ALLDAY) ? true : false,
                    'id' => ($item instanceof Public_holiday) ? '' : $item->id,
                    'user_id' => $item->user_id ?? '',
                    'project_id' => $item->project_id ?? '',
                    'sub_project_id' => $item->sub_project_id ?? '',
                    'prod_routing_id' => $item->prod_routing_id ?? '',
                    'lod_id' => $item->lod_id ?? '',
                    'discipline_id' => $item->discipline_id ?? '',
                    'task_id' => $item->task_id ?? '',
                    'sub_task_id' => $item->sub_task_id ?? '',
                    'work_mode_id' => $item->work_mode_id ?? '',
                    'color' => $item->work_mode_id ? Calendar::setColorByWorkModeId($item->work_mode_id) : '#94a3b8',
                    'remark' => $item->remark ?? '',
                    'owner_id' => $item->owner_id ?? '',
                    'status' => $item->status ?? '',
                    'public_holiday' => ($item instanceof Public_holiday) ? true : false,
                ];
            }),
        ];
    }
}
