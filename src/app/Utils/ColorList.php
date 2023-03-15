<?php

namespace App\Utils;

class ColorList
{
    static function getBgColorForRemainingOTHours($value)
    {
        switch (true) {
            case $value <= 0:
                return 'bg-red-600';
            case $value <= 10:
                return 'bg-pink-400';
            case $value <= 20:
                return 'bg-orange-300';
            case $value <= 30:
                return 'bg-yellow-300';
            case $value > 30:
                return 'bg-green-300';
        }
        return 'bg-gray-100';
    }
}
