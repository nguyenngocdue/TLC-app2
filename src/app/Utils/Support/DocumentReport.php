<?php

namespace App\Utils\Support;

use DateTime;
use Illuminate\Support\Str;

class DocumentReport
{
    public static function getCurrentMonthYear() {
        $currentDate = new DateTime();
        $formattedDate = $currentDate->format("Y-m");
        return $formattedDate;
    }
    
}
