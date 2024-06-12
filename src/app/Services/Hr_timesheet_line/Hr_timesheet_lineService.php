<?php

namespace App\Services\Hr_timesheet_line;

use App\Repositories\Hr_timesheet_line\Hr_timesheet_lineRepositoryInterface;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Hr_timesheet_lineService extends BaseService implements Hr_timesheet_lineServiceInterface
{
    protected $hrTimesheetLineRepository;
    public function __construct(Hr_timesheet_lineRepositoryInterface $hrTimesheetLineRepository)
    {
        $this->hrTimesheetLineRepository = $hrTimesheetLineRepository;
    }
    public function find($id)
    {
        return $this->hrTimesheetLineRepository->find($id);
    }
    public function create($request)
    {
        return $this->hrTimesheetLineRepository->create($request);
    }
    public function update($id, $data)
    {
        return $this->hrTimesheetLineRepository->update($id, $data);
    }
    public function delete($id)
    {
        return $this->hrTimesheetLineRepository->delete($id);
    }
    public function duplicate($id)
    {
        $model = $this->find($id);
        $startTimeOld = $model->start_time;
        $newModel = $model->replicate();
        $newModel->start_time = $this->addDay($startTimeOld);
        $newModel->save();
        return $newModel;
    }
    private function addDay($startTime)
    {
        $dateTime = Carbon::parse($startTime);
        $dateTime->addDay();
        return $dateTime->toDateTimeString();
    }
}
