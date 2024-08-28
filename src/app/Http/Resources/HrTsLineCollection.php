<?php

namespace App\Http\Resources;

use App\Models\Diginet_employee_leave_line;
use App\Models\Hr_leave_line;
use App\Models\Hr_timesheet_officer_line;
use App\Models\Pj_task;
use App\Models\Public_holiday;
use App\Models\User;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Log;

class HrTsLineCollection extends ResourceCollection
{

    private function getStartEndForNZ($item)
    {
        $HALF_DAY_AFTERNOON_ID = 3;
        $la_date = $item->leave_date;
        $number_of_la_day = $item->leave_days;
        // Log::info($la_date . " + " . $number_of_la_day);
        // Log::info($number_of_la_day * 8 * 60);

        $workplace = User::getFirstBy('id', $item->user_id, ["getWorkplace"])->getWorkplace;
        $startTimeStr = $la_date . " " . $workplace->standard_start_time;
        $startTime = Carbon::createFromFormat('Y-m-d H:i:s', $startTimeStr, 'UTC')
            ->subDays(1);
        if ($item->leave_type_id == $HALF_DAY_AFTERNOON_ID) {
            $startTime = $startTime->addHour(5);
        }

        $endTime = Carbon::createFromFormat('Y-m-d H:i:s', $startTimeStr, 'UTC')
            ->addMinute($number_of_la_day * 8 * 60)
            ->subDays(1);
        // ->addHour(4);
        if ($item->leave_type_id == $HALF_DAY_AFTERNOON_ID) {
            $endTime = $endTime->addHour(5);
        }
        if ($number_of_la_day > 0.5)
            $endTime = $endTime->addMinute($workplace->break_duration_in_min);
        $endTime = $endTime->format('Y-m-d H:i:s');
        // Log::info($startTime . " -> " . $endTime);
        $s = DateTimeConcern::formatTimestampFromDBtoJS($startTime);
        $e = DateTimeConcern::formatTimestampFromDBtoJS($endTime);
        $result = [$s, $e];
        return $result;
    }

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
                // $employeeId = $item->employeeid;
                // $user = User::getByEmployeeId($employeeId);
                // $workplaceId = $user->workplace;
                // $workplace = Workplace::find($workplaceId);
                $workplace = User::getFirstBy('employeeid', $item->employeeid, ["getWorkplace"])->getWorkplace;
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
            default:
                Log::info("Unexpected number_of_la_day: $number_of_la_day");
                return null;
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
                    'sub_title' => Calendar::renderSubTitle($item),
                    'tag_sub_project' => Calendar::renderSubProject($item) ?? '',
                    'tag_phase' => Calendar::renderPhase($item) ?? '',
                    // 'name_project' => Calendar::renderNameProject($item) ?? '',
                    'user_id' => $item->user_id ?? '',
                    'project_id' => $item->project_id ?? '',
                    'sub_project_id' => $item->sub_project_id ?? '',
                    // 'prod_routing_id' => $item->prod_routing_id ?? '',
                    'lod_id' => $item->lod_id ?? '',
                    'discipline_id' => $item->discipline_id ?? '',
                    'task_id' => $item->task_id ?? '',
                    'sub_task_id' => $item->sub_task_id ?? '',
                    'work_mode_id' => $item->work_mode_id ?? '',
                    'remark' => $item->remark ?? '',
                    'owner_id' => $item->owner_id ?? '',
                    'status' => $item->status ?? '',
                ];

                switch (true) {
                    case ($item instanceof Public_holiday):
                        $item1['id'] = '';
                        $item1['is_ph_or_la'] = true;
                        $item1['day_count'] = 1;
                        $item1['start'] = DateTimeConcern::formatTimestampFromDBtoJSForPH($item);
                        $item1['end'] = DateTimeConcern::calcEndTimeForPH($item);
                        $item1['color'] = '#BA3C36';
                        return $item1;
                    case ($item instanceof Diginet_employee_leave_line):
                        $values = $this->getStartEndFromLADayAndWP($item);
                        //In case if leave is standard 0.53 or 0.47 or 0.56 or 0.44 or 1
                        if ($values) {
                            $item1['id'] = '';
                            $item1['is_ph_or_la'] = true;
                            $item1['day_count'] = $item->number_of_la_day;
                            [$start, $end] = $values;
                            $item1['start'] = $start;
                            $item1['end'] = $end;
                            $item1['color'] = '#26C560';
                        } // no need else
                        return $item1;
                    case ($item instanceof Hr_leave_line):
                        $values = $this->getStartEndForNZ($item);
                        //In case if leave is standard 0.53 or 0.47 or 0.56 or 0.44 or 1
                        if ($values) {
                            $item1['id'] = '';
                            $item1['is_ph_or_la'] = true;
                            $item1['day_count'] = $item->leave_days;
                            [$start, $end] = $values;
                            $item1['start'] = $start;
                            $item1['end'] = $end;
                            $item1['color'] = '#26C560';
                        }
                        return $item1;
                    case ($item instanceof Hr_timesheet_officer_line):
                        $item1['id'] = $item->id;
                        $item1['is_ph_or_la'] = false;
                        $item1['day_count'] = 99999;
                        $item1['start'] = DateTimeConcern::formatTimestampFromDBtoJS($item->start_time);
                        $item1['end'] = DateTimeConcern::calcEndTime($item->start_time, $item->duration_in_min);
                        $item1['color'] = Calendar::getBkColorByWorkModeId($item->work_mode_id);
                        $item1['title_default'] = Pj_task::findOrFail($item->task_id)->name;
                        // Log::info($item1);
                        return $item1;
                    default:
                        Log::info("Unexpected class: " . get_class($item));
                        return $item1;
                }
            }),
        ];
    }
}
