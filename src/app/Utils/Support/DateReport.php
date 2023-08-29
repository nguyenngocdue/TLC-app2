<?php

namespace App\Utils\Support;

use DateTime;

class DateReport
{
    public static function getShortDayOfWeek($dateString) {
        $date = DateTime::createFromFormat('d/m/Y', $dateString);
        $dayOfWeek = $date->format('D');
        return $dayOfWeek;
    }

    
}
