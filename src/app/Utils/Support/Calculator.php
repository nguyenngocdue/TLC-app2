<?php

namespace App\Utils\Support;

use Carbon\Carbon;

trait Calculator
{
    /**
     * Convert timestamps from database format to javascript format (Full Calendar)
     *
     * @param mixed $timestamp
     * @return string
     */
    public function formatTimestampFromDBtoJS($timestamp)
    {
        return str_replace(' ', 'T', $timestamp) . 'Z';
    }

    /**
     * Convert timestamps from javascript format (Full Calendar) to database format
     *
     * @param mixed $timestamp
     * @return string
     */
    public function formatTimestampFromJStoDB($timestamp)
    {
        $dateTime = Carbon::parse($timestamp);
        $utcDate = $dateTime->utc();
        return $utcDate->toDateTimeString();
    }
    /**
     * Calculate duration based on start time and end time  
     *
     * @param mixed $startTime
     * @param mixed $endTime
     * @return int
     */
    public function calDurationFromStartTimeAndEndTime($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);
        return $endDateTime->diffInMinutes($startDateTime);
    }
    /**
     * Calculate end time based on start time and duration
     *
     * @param mixed $startTime
     * @param int $duration
     * @return string
     */
    public function calTimestampEndFromStartTimeAndDuration($startTime, $duration)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = $startDateTime->addMinute($duration);
        return $this->formatTimestampFromDBtoJS($endDateTime->format('Y-m-d H:i:s'));
    }

    /**
     * Check timestamp has correct format required
     *
     * @param mixed $timestamp
     * @return boolean
     */
    public function isFormatJsDateTime($timestamp)
    {
        return date_create_from_format("Y-m-d\TH:i:sP", $timestamp);
    }
}
