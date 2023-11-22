<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Utils\Support\JsonControls;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

trait TraitSendNotificationAndMail
{
    // private function getPreviousValue($fields, $item)
    // {
    //     $previousValue = [];
    //     foreach ($fields as $key => $value) {
    //         if ($key !== 'tableNames') {
    //             if (isset($item->$key)) {
    //                 if (($item->$key) instanceof Collection) {
    //                     unset($previousValue[$key]);
    //                 } else {
    //                     $previousValue[$key] = $item->$key;
    //                 }
    //             } else {
    //                 if (in_array($key, JsonControls::getMonitors())) {
    //                     $fn = str_replace('()', '', $key);
    //                     $valueGetMonitors = $item->{$fn}()->pluck('id')->toArray();
    //                     $previousValue[$key] = $valueGetMonitors;
    //                 }
    //             }
    //         } else {
    //             $previousValue[$key] = $value;
    //         }
    //     }
    //     return $previousValue;
    // }
}
