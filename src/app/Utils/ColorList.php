<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class ColorList
{
    static function getBgColorForRemainingOTHours($hours, $max)
    {
        $value = $hours / $max * 100;
        switch (true) {
            case $value < 0:
                return 'bg-red-600';
            case $value <= 25:
                return 'bg-pink-400';
            case $value <= 50:
                return 'bg-orange-300';
            case $value <= 75:
                return 'bg-yellow-300';
            case $value > 75:
                return 'bg-green-300';
        }
        return 'bg-gray-100';
    }
}
