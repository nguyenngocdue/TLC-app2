<?php

namespace App\Utils\Support;

use Carbon\Carbon;

trait Calculator
{
    public function formatTimestampFromDBtoJS($timestamp)
    {
        return str_replace(' ', 'T', $timestamp) . 'Z';
    }
    public function formatTimestampFromJStoDB($timestamp)
    {
        $dateTime = Carbon::parse($timestamp);
        $utcDate = $dateTime->utc();
        return $utcDate->toDateTimeString();
    }
    public function calDurationFromStartTimeAndEndTime($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);
        return $endDateTime->diffInMinutes($startDateTime);
    }
    public function calTimestampEndFromStartTimeAndDuration($startTime, $duration)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = $startDateTime->addMinute($duration);
        return $this->formatTimestampFromDBtoJS($endDateTime->format('Y-m-d H:i:s'));
    }
    public function isFormatJsDateTime($timestamp)
    {
        return date_create_from_format("Y-m-d\TH:i:sP", $timestamp);
    }
}
