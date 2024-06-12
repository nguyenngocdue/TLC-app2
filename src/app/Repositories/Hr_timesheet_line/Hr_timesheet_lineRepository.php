<?php

namespace App\Repositories\Hr_timesheet_line;

use App\Models\Hr_timesheet_officer_line;
use App\Repositories\BaseRepository;

class Hr_timesheet_lineRepository extends BaseRepository implements Hr_timesheet_lineRepositoryInterface
{
    public function getModel()
    {
        return Hr_timesheet_officer_line::class;
    }
}
