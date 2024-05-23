<?php

namespace App\Http\Resources;

use App\Models\Diginet_employee_leave_line;
use App\Models\Pj_task;
use App\Models\Public_holiday;
use App\Models\User;
use App\Models\Workplace;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class HrTsLineCollection extends ResourceCollection
{

    private function getStartEndFromLADayAndWP($item)
    {
        $la_date = $item->la_date;
        $number_of_la_day = $item->number_of_la_day;
        // Log::info($number_of_la_day);

        // HO: Morning    2024-05-03T01:00:00Z to 2024-05-03T05:00:00Z
        // HO: Afternoon: 2024-05-03T06:00:00Z to 2024-05-03T10:30:00Z
        switch ($number_of_la_day) {
            case 0.47:
                return [$la_date . 'T01:00:00Z', $la_date . 'T05:00:00Z'];
            case 0.53:
                return [$la_date . 'T06:00:00Z', $la_date . 'T10:30:00Z'];
            case 0.56:
                return [$la_date . 'T00:00:00Z', $la_date . 'T04:30:00Z'];
            case 0.44:
                return [$la_date . 'T05:30:00Z', $la_date . 'T09:00:00Z'];
            case 1:
                $employeeId = $item->employeeid;
                $user = User::getByEmployeeId($employeeId);
                $workplaceId = $user->workplace;
                $workplace = Workplace::find($workplaceId);
                // Log::info($workplace);
                $startTime = $workplace->standard_start_time;
                $endTime = Carbon::createFromDate($workplace->standard_start_time)
                    ->addMinute($workplace->standard_working_min)
                    ->addMinute($workplace->break_duration_in_min)
                    ->format('H:i:s');
                $result = [$la_date . "T" . $startTime . "Z", $la_date . "T" . $endTime . "Z"];
                // Log::info($result);
                return $result;
                // return ['2024-05-03T01:00:00Z', '2024-05-03T02:00:00Z'];
        }
        return null;
    }
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
                // Log::info($item);
                $item1 =  [
                    'title' => Calendar::renderTitle($item),
                    'tag_sub_project' => Calendar::renderTagSubProject($item) ?? '',
                    'name_project' => Calendar::renderNameProject($item) ?? '',
                    'user_id' => $item->user_id ?? '',
                    'project_id' => $item->project_id ?? '',
                    'sub_project_id' => $item->sub_project_id ?? '',
                    'prod_routing_id' => $item->prod_routing_id ?? '',
                    'lod_id' => $item->lod_id ?? '',
                    'discipline_id' => $item->discipline_id ?? '',
                    'task_id' => $item->task_id ?? '',
                    'sub_task_id' => $item->sub_task_id ?? '',
                    'work_mode_id' => $item->work_mode_id ?? '',
                    'remark' => $item->remark ?? '',
                    'owner_id' => $item->owner_id ?? '',
                    'status' => $item->status ?? '',
                ];

                if ($item instanceof Public_holiday) {
                    $item1['id'] = '';
                    $item1['public_holiday'] = true;
                    $item1['start'] = DateTimeConcern::formatTimestampFromDBtoJSForPH($item);
                    $item1['end'] = DateTimeConcern::calTimestampEndFromStartTimeAndDurationForPH($item);
                    $item1['color'] = '#BA3C36';
                } elseif ($item instanceof Diginet_employee_leave_line) {
                    $values = $this->getStartEndFromLADayAndWP($item);
                    //In case if leave is standard 0.53 or 0.47 or 0.56 or 0.44 or 1
                    if ($values) {
                        $item1['id'] = '';
                        $item1['public_holiday'] = true;
                        [$start, $end] = $this->getStartEndFromLADayAndWP($item);
                        $item1['start'] = $start;
                        $item1['end'] = $end;
                        $item1['color'] = '#26C560';
                    }
                } else {
                    $item1['id'] = $item->id;
                    $item1['public_holiday'] = false;
                    $item1['start'] = DateTimeConcern::formatTimestampFromDBtoJS($item->start_time);
                    $item1['end'] = DateTimeConcern::calTimestampEndFromStartTimeAndDuration($item->start_time, $item->duration_in_min);
                    $item1['color'] = Calendar::setColorByWorkModeId($item->work_mode_id);
                    $item1['title_default'] = Pj_task::findOrFail($item->task_id)->name;
                }

                return $item1;
            }),
        ];
    }
}
