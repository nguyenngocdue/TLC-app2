<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\ConvertColorTailwind;

class LibStatuses extends AbstractLibForForm
{
    protected static $key = "statuses";

    public static function getAllWithHex()
    {
        $data = parent::getAll();
        foreach ($data as &$line) {
            $text_index = $line['color'] . "-" . $line["color_index"];
            $line['text_color_hex'] = ConvertColorTailwind::$colors[$text_index] ?? "red";
            $bg_index = $line['color'] . "-" . (1000 - $line["color_index"]);
            $line['bg_color_hex'] = ConvertColorTailwind::$colors[$bg_index] ?? "red";
        }
        return $data;
    }
}
