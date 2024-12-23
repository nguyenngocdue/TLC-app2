<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitViewAllFunctions;
use App\Http\Resources\HrTsLineCollection;
use App\Models\Diginet_employee_leave_line;
use App\Models\Hr_leave_line;
use App\Models\Hr_timesheet_officer;
use App\Models\Public_holiday;
use App\Models\User;
use App\Utils\Support\CurrentUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimeSheetOfficerController extends TimesheetController
{
    use TraitViewAllFunctions;
    protected $type = 'hr_timesheet_officers';
    protected $model = Hr_timesheet_officer::class;

    private function getPublicHoliday($hrTimesheetOfficer)
    {
        [, $filterViewAllCalendar] = $this->getUserSettingsViewAllCalendar();
        $year = $filterViewAllCalendar['year'] ?? Carbon::now()->format('Y');

        $ownerId = $hrTimesheetOfficer->owner_id ?? CurrentUser::id();
        $workplaceId = User::findFromCache($ownerId)->workplace;
        $publicHoliday = Public_holiday::where('year', $year)->where('workplace_id', $workplaceId)->get();
        return $publicHoliday;
    }

    private function getLeaveApplication($hrTimesheetOfficer)
    {
        // Log::info($hrTimesheetOfficer);
        $ownerId = $hrTimesheetOfficer->owner_id;
        $user = User::findFromCache($ownerId);
        // Log::info($user);
        $employeeId = $user->employeeid;

        //For VIETNAM staff:
        $x = (new Diginet_employee_leave_line())->getLinesByEmployeeIdAndRange($employeeId);
        $x = $x->filter(fn($y) => $y->la_type != 'WFH');

        //For NZ staff:
        $y = Hr_leave_line::query()
            ->where("user_id", $ownerId)
            ->with("getLeaveType")
            ->get();

        $moreLines = $x;
        foreach ($y as $z) $moreLines[] = $z;
        return $moreLines;
    }

    public function show(Request $request, $id)
    {
        $hrTimesheetOfficer = Hr_timesheet_officer::findOrFail($id);
        $hrTsLines = $hrTimesheetOfficer->getHrTsLines;

        $ph = $this->getPublicHoliday($hrTimesheetOfficer);
        $collectionMerge = $hrTsLines->merge($ph);

        $la = $this->getLeaveApplication($hrTimesheetOfficer);
        $collectionMerge = $collectionMerge->merge($la);

        $initialDate = $hrTimesheetOfficer->week;
        $hits = new HrTsLineCollection($collectionMerge);

        // Log::info($hits);
        return ['hits' => $hits, 'meta' => $initialDate,];
    }
}
