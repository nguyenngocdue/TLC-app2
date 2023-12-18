<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\ConvertColorTailwind;
use Illuminate\Support\Facades\Log;

class LibStatuses extends AbstractLibForForm
{
    protected static $key = "statuses";

    public static function getAll()
    {
        $data = parent::getAll();
        // dd($data);
        foreach ($data as &$line) {
            // dump($line['color']);
            $color = $line['color'] ?? 'red';
            $color_index = $line['color_index'] ?? 400;
            $text_index = $color . "-" . $color_index;
            $bg_index = $color . "-" . (1000 - $color_index);

            $line['bg_color'] = $text_index;
            $line['text_color'] = $bg_index;
        }
        return $data;
    }

    public static function getAllWithHex()
    {
        $data = parent::getAll();
        foreach ($data as &$line) {
            $text_index = $line['color'] . "-" . $line["color_index"];
            $bg_index = $line['color'] . "-" . (1000 - $line["color_index"]);

            $line['text_color_hex'] = ConvertColorTailwind::$colors[$text_index] ?? "red";
            $line['bg_color_hex'] = ConvertColorTailwind::$colors[$bg_index] ?? "red";
        }
        return $data;
    }
}
