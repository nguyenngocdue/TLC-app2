<?php

namespace App\View\Components\Realtime;

class OTRL_RemainingHours
{
    function __invoke($item)
    {
        return $item->getRemainingHours->remaining_hours;
    }
}
