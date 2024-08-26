<?php

namespace App\Http\Resources;

use App\Models\Pj_task;
use App\Utils\Constant;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\JsonResource;

class TimesheetLineResource extends JsonResource
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
            'title' => Calendar::renderTitle($this),
            'sub_title' => Calendar::renderSubTitle($this),
            'title_default' => Pj_task::findOrFail($this->task_id)->name,
            'tag_sub_project' => Calendar::renderSubProject($this),
            'tag_phase' => Calendar::renderPhase($this),
            'start' => $this->start_time ? DateTimeConcern::formatTimestampFromDBtoJS($this->start_time) : null,
            'end' => $this->start_time ? DateTimeConcern::calcEndTime($this->start_time, $this->duration_in_min) : null,
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'sub_project_id' => $this->sub_project_id,
            'prod_routing_id' => $this->prod_routing_id,
            'lod_id' => $this->lod_id,
            'discipline_id' => $this->discipline_id,
            'task_id' => $this->task_id,
            'sub_task_id' => $this->sub_task_id,
            'work_mode_id' => $this->work_mode_id,
            'color' => Calendar::getBkColorByWorkModeId($this->work_mode_id),
            'remark' => $this->remark,
            'owner_id' => $this->owner_id,
            'status' => $this->status,
        ];
    }
}
