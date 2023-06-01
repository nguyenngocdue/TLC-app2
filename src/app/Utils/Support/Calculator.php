<?php

namespace App\Utils\Support;

use Carbon\Carbon;

trait Calculator
{
    public function formatTimestampFromDBtoJS($timestamp)
    {
        return str_replace(' ', 'T', $timestamp);
    }
    public function calTimestampEndFromStartTimeAndDuration($startTime, $duration)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = $startDateTime->addMinute($duration);
        return $this->formatTimestampFromDBtoJS($endDateTime->format('Y-m-d H:i:s'));
    }
}
