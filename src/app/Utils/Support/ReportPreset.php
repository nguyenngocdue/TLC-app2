<?php

namespace App\Utils\Support;

use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Date;
use InvalidArgumentException;

class ReportPreset
{
    public static function generateDateRange($timeFrame, $toDate, $fromDate)
    {
        switch ($timeFrame) {
            case 'today':
                $fromDate->setTime(0, 0, 0); // Start of today
                $toDate->setTime(23, 59, 59); // End of today
                break;
            case 'yesterday':
                $fromDate->modify('-1 day');
                $toDate->modify('-1 day')->setTime(23, 59, 59);
                $fromDate->setTime(0, 0, 0);
                break;
            case 'last_2_days':
                $fromDate->modify('-2 days')->setTime(0, 0, 0);
                $toDate->setTime(23, 59, 59);
                break;
            case 'last_week':
                $fromDate->modify('last week')->setTime(0, 0, 0);
                $toDate->modify('last week +6 days')->setTime(23, 59, 59);
                break;
            case 'last_month':
                $fromDate->modify('first day of last month')->setTime(0, 0, 0);
                $toDate->modify('last day of last month')->setTime(23, 59, 59);
                break;
            case 'last_year':
                $fromDate->modify('first day of January last year')->setTime(0, 0, 0);
                $toDate->modify('last day of December last year')->setTime(23, 59, 59);
                break;
            case 'last_2_years':
                $fromDate->modify('first day of January')->modify('-2 years')->setTime(0, 0, 0);
                $toDate->modify('last day of December last year')->setTime(23, 59, 59);
                break;
            case 'last_5_minutes':
                $fromDate->modify('-5 minutes'); // 5 minutes ago
                break;
            case 'last_15_minutes':
                $fromDate->modify('-15 minutes'); // 15 minutes ago
                break;
            case 'last_30_minutes':
                $fromDate->modify('-30 minutes'); // 30 minutes ago
                break;
            case 'last_1_hour':
                $fromDate->modify('-1 hour'); // 1 hour ago
                break;
            case 'last_3_hours':
                $fromDate->modify('-3 hours'); // 3 hours ago
                break;
            case 'last_6_hours':
                $fromDate->modify('-6 hours'); // 6 hours ago
                break;
            case 'last_12_hours':
                $fromDate->modify('-12 hours'); // 12 hours ago
                break;
            default:
                $fromDate = $fromDate;
        }

        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }
    public static function getTimezoneFromOffset($timezone) {
        if (preg_match('/UTC([+-])(\d+)/', $timezone, $matches)) {
            $sign = $matches[1] === '+' ? '-' : '+';
            return 'Etc/GMT' . $sign . $matches[2];
        }
        return 'UTC';
    }

    public static function getDateOfToday($timezone = null, $toDate = null) {
        $timezoneObj = new DateTimeZone(self::getTimezoneFromOffset($timezone));
        $fromDate = new DateTime('today 00:00:00', $timezoneObj);
        if (!$toDate) $toDate = new DateTime('now', $timezoneObj);
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }

    public static function getDateOfThisWeek($timezone = null, $toDate = null) {
        $timezoneString = self::getTimezoneFromOffset($timezone);
        $timezoneObj = new DateTimeZone($timezoneString);
        if ($toDate === null) {
            $toDate = new DateTime($toDate, $timezoneObj);
            $toDate->modify('this week Sunday')->setTime(23, 59, 59); // Sunday, 23:59:59
        }
        $fromDate = clone $toDate;
        $fromDate->modify('this week Monday')->setTime(0, 0, 0); // Monday, 00:00:00
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }

    public static function getDateOfThisMonth($timezone = null, $toDate = null) {
        $timezoneObj = new DateTimeZone(self::getTimezoneFromOffset($timezone));
        if ($toDate === null) {
            $toDate = new DateTime('now', $timezoneObj);
            $toDate->modify('last day of this month')->setTime(23, 59, 59);
        }
        $fromDate = clone $toDate;
        $fromDate->modify('first day of this month')->setTime(0, 0, 0);
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }

    public static function getDateOfThisYear($timezone = null, $toDate= null) {
        $timezoneObj = $timezone ? new DateTimeZone(self::getTimezoneFromOffset($timezone)) : new DateTimeZone(date_default_timezone_get());
        // Initialize DateTime with the correct timezone
        $fromDate = new DateTime('now', $timezoneObj);
        $fromDate->modify('first day of January this year')->setTime(0, 0, 0);
        if (!$toDate){
            $toDate = clone $fromDate;
            $toDate->modify('last day of December this year')->setTime(23, 59, 59);
        }
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }

    public static function getDateOfPrevious3Months($timezone = null, $toDate = null) {
        $timezoneObj = $timezone ? new DateTimeZone(self::getTimezoneFromOffset($timezone)) : new DateTimeZone(date_default_timezone_get());
        $toDate = new DateTime('now', $timezoneObj);
        $fromDate = (clone $toDate)->modify('-3 months');
    
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s'),
        ];
    }
    

    public static function getDateThisQuarter($timezone = null, $toDate = null) {
        $timezoneObj = $timezone ? new DateTimeZone(self::getTimezoneFromOffset($timezone)) : new DateTimeZone(date_default_timezone_get());
        $hasToDate = true;
        if (!$toDate) {
            $date = new DateTime('now', $timezoneObj);
            $hasToDate = false;
        } else {
            $date = $toDate;
        }
        // Determine the current month and year
        $currentMonth = (int) $date->format('n');
        $currentYear = (int) $date->format('Y');

        $fromDate = clone $date;
        // Determine the start and end of the quarter
        if ($currentMonth >= 1 && $currentMonth <= 3) {
            // Q1: January 1st to March 31st
            $fromDate->setDate($currentYear, 1, 1);
            $toDate = (clone $fromDate)->setDate($currentYear, 3, 31);
        } elseif ($currentMonth >= 4 && $currentMonth <= 6) {
            // Q2: April 1st to June 30th
            $fromDate->setDate($currentYear, 4, 1);
            $toDate = (clone $fromDate)->setDate($currentYear, 6, 30);
        } elseif ($currentMonth >= 7 && $currentMonth <= 9) {
            // Q3: July 1st to September 30th
            $fromDate->setDate($currentYear, 7, 1);
            $toDate = (clone $fromDate)->setDate($currentYear, 9, 30);
        } else {
            // Q4: October 1st to December 31st
            $fromDate->setDate($currentYear, 10, 1);
            $toDate = (clone $fromDate)->setDate($currentYear, 12, 31);
        }
        // Set times to start and end of the day
        $fromDate->setTime(0, 0, 0);
        if (!$hasToDate) $toDate->setTime(23, 59, 59);
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }
    
    public static function getDateForQuarter($quarter, $timezone = null) {
        $timezoneObj = $timezone ? new DateTimeZone(self::getTimezoneFromOffset($timezone)) : new DateTimeZone(date_default_timezone_get());
        $currentYear = (new DateTime('now', $timezoneObj))->format('Y');
        $fromDate = null;
        $toDate = null;
        switch ($quarter) {
            case 1: // First quarter (Q1: January 1st to March 31st)
                $fromDate = new DateTime("{$currentYear}-01-01 00:00:00", $timezoneObj);
                $toDate = new DateTime("{$currentYear}-03-31 23:59:59", $timezoneObj);
                break;

            case 2: // Second quarter (Q2: April 1st to June 30th)
                $fromDate = new DateTime("{$currentYear}-04-01 00:00:00", $timezoneObj);
                $toDate = new DateTime("{$currentYear}-06-30 23:59:59", $timezoneObj);
                break;

            case 3: // Third quarter (Q3: July 1st to September 30th)
                $fromDate = new DateTime("{$currentYear}-07-01 00:00:00", $timezoneObj);
                $toDate = new DateTime("{$currentYear}-09-30 23:59:59", $timezoneObj);
                break;

            case 4: // Fourth quarter (Q4: October 1st to December 31st)
                $fromDate = new DateTime("{$currentYear}-10-01 00:00:00", $timezoneObj);
                $toDate = new DateTime("{$currentYear}-12-31 23:59:59", $timezoneObj);
                break;

            default:
                throw new InvalidArgumentException('Invalid quarter. Please use 1, 2, 3, or 4.');
        }

        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }

    public static function getDateOfHalfYear($half, $timezone = null) {
        $timezoneObj = $timezone ? new DateTimeZone(self::getTimezoneFromOffset($timezone)) : new DateTimeZone(date_default_timezone_get());
        $currentYear = (new DateTime('now', $timezoneObj))->format('Y');
        $fromDate = null;
        $toDate = null;
        switch ($half) {
            case 'first_half': // First half of the year (January 1st - June 30th)
                $fromDate = new DateTime("{$currentYear}-01-01 00:00:00", $timezoneObj); // January 1st
                $toDate = new DateTime("{$currentYear}-06-30 23:59:59", $timezoneObj);  // June 30th
                break;

            case 'second_half': // Second half of the year (July 1st - December 31st)
                $fromDate = new DateTime("{$currentYear}-07-01 00:00:00", $timezoneObj); // July 1st
                $toDate = new DateTime("{$currentYear}-12-31 23:59:59", $timezoneObj);  // December 31st
                break;

            default:
                throw new InvalidArgumentException('Invalid half. Please use "first_half" or "second_half".');
        }
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }


    public static function getAllTime($timezone = null, $toDate = null) {
        $timezoneObj = new DateTimeZone(self::getTimezoneFromOffset($timezone));
        if (!$toDate) $toDate = new DateTime('now', $timezoneObj);
        $fromDate = (new DateTime())->setTimestamp(0);
        return [
            'from_date' => $fromDate->format('Y-m-d H:i:s'),
            'to_date' => $toDate->format('Y-m-d H:i:s')
        ];
    }


    



}
