<?php

namespace App\Services\Hr_timesheet_line;

use App\Repositories\Hr_timesheet_line\Hr_timesheet_lineRepositoryInterface;
use App\Services\BaseService;

class Hr_timesheet_lineService extends BaseService implements Hr_timesheet_lineServiceInterface
{
    protected $hrTimesheetLineRepository;
    public function __construct(Hr_timesheet_lineRepositoryInterface $hrTimesheetLineRepository)
    {
        $this->hrTimesheetLineRepository = $hrTimesheetLineRepository;
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
}
