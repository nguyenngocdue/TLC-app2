<?php

namespace App\Http\Controllers\Api\v1\HR;

use App\Http\Controllers\Controller;
use App\Http\Resources\HrTsLineStoreResource;
use App\Http\Resources\HrTsLineUpdateResource;
use App\Http\Resources\TimesheetLineResource;
use App\Models\User_discipline;
use App\Services\Hr_timesheet_line\Hr_timesheet_lineServiceInterface;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Definitions;
use App\Utils\System\Api\ResponseObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class TimesheetController extends Controller
{
    protected $type;
    protected $model;
    public $timesheetLineService;
    public function __construct(Hr_timesheet_lineServiceInterface $timesheetLineService)
    {
        $this->timesheetLineService = $timesheetLineService;
    }
    /**
     * abstract function show return data (all Timesheet line by model Hr_timesheet_officer or Hr_timesheet_worker)
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    abstract protected function show(Request $request, $id);


    public function create(Request $request)
    {
        $week = $request->input('week');
        try {
            if ($week) {
                $date = Carbon::parse($week);
                $dateStartOfWeek = Carbon::parse($week)->startOfWeek()->format('Y-m-d');
                $currentUser = CurrentUser::get();
                $teamId = $currentUser->category;
                $userDisciplineId = $currentUser->discipline;
                $assignee = User_discipline::findOrFail($userDisciplineId)->def_assignee;
                $definitions = Definitions::getAllOf($this->type)['new'] ?? ['name' => '', 'new' => true];
                array_shift($definitions);
                $statuses = array_keys(array_filter($definitions, fn ($item) => $item));
                if ($dateStartOfWeek == $week) {
                    $timesheet = ($this->model)::create([
                        'week' => $week,
                        'team_id' => $teamId,
                        'assignee_1' => $assignee,
                        'owner_id' => CurrentUser::id(),
                        'status' => $statuses[0] ?? 'new',
                    ]);
                } else {
                    if ($date->day == 26) {
                        $timesheet = ($this->model)::create([
                            'week' => $week,
                            'team_id' => $teamId,
                            'assignee' => $assignee,
                            'owner_id' => CurrentUser::id(),
                            'status' => $statuses[0] ?? 'new',
                        ]);
                    } else {
                        return ResponseObject::responseFail(
                            'Invalid format Datetime please enter correct format',
                        );
                    }
                }
                $url = route($this->type . '.edit', $timesheet->id);
                return ResponseObject::responseSuccess(
                    $url,
                    [],
                    'Created Timesheet successfully!'
                );
            }
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }

    /**
     * Create new Timesheet Line document
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $resource = new HrTsLineStoreResource($request);
        $data = $resource->toArray($request);
        try {
            $timesheetLine = $this->timesheetLineService->create($data);
            return new TimesheetLineResource($timesheetLine);
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    /**
     * Update fields give Timesheet Line by document id
     *
     * @param Request $request
     * @param mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        if ($request->input('time_type') == 'full_day') {
            $results = [];
            $timeTypes = ['morning', 'afternoon'];
            $request->merge(['time_type' => $timeTypes[0]]);
            $results['data'][] = $this->updateTimesheetLine($request, $id);
            $request->merge(['time_type' => $timeTypes[1]]);
            $timesheetLine = $this->timesheetLineService->duplicate($id);
            $results['data'][] = $this->updateTimesheetLine($request, $timesheetLine->id);
            return $results;
        } else {
            return $this->updateTimesheetLine($request, $id);
        }
    }

    private function updateTimesheetLine($request, $id)
    {
        $resource = new HrTsLineUpdateResource($request);
        $data = $resource->toArray($request);
        $data = array_filter($data, fn ($item) => $item);
        try {
            $this->timesheetLineService->update($id, $data);
            return new TimesheetLineResource($this->timesheetLineService->find($id));
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
    /**
     * Delete Timesheet Line by document id
     *
     * @param mixed $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $this->timesheetLineService->delete($id);
            return ResponseObject::responseSuccess(
                null,
                [],
                'Deleted timesheet line ' . $id . ' successfully'
            );
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }

    public function duplicate($id)
    {
        try {
            $timesheetLine = $this->timesheetLineService->duplicate($id);
            return new TimesheetLineResource($timesheetLine);
        } catch (\Throwable $th) {
            return ResponseObject::responseFail(
                $th->getMessage(),
            );
        }
    }
}
