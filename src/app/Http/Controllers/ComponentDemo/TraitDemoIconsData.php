<?php

namespace App\Http\Controllers\ComponentDemo;

use Illuminate\Support\Facades\Log;

trait TraitDemoIconsData
{
    function getIconsColumns()
    {
        return [
            [
                'dataIndex' => 'name',
            ],
            [
                'dataIndex' => 'solid',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'regular',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'light',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'thin',
                'align' => 'center',
            ],
            [
                'dataIndex' => 'duotone',
                'align' => 'center',
            ],
        ];
    }

    function insertSubstring($originalString, $substring, $position)
    {
        if ($position < 0 || $position > strlen($originalString)) {
            return $originalString; // Invalid position, return the original string
        }

        $part1 = substr($originalString, 0, $position);
        $part2 = substr($originalString, $position);

        return $part1 . $substring . $part2;
    }

    function makeCopyableIcon($type, $icon, $index, $style = "")
    {
        $solid = "<i class=\"fa-$type fa-$icon\"></i>";
        echo "<script>const icon_{$type}_$index='$solid';</script>\n";
        $solid = $this->insertSubstring($solid, "text-5xl ", 10);
        if ($style) $solid = $this->insertSubstring($solid, "style='$style' ", 3);
        $solidBtn = "<button type='button' class='hover:bg-blue-400 rounded p-1' onclick=\"setClipboardValue(icon_{$type}_$index)\">" . $solid . "</button>";

        return "$solidBtn";
    }

    function getIconsDataSource()
    {
        $icons = config("icons");
        $result = [];
        foreach ($icons as $index => $icon) {

            $result[] = [
                'name' => $icon,
                'solid' => $this->makeCopyableIcon("solid", $icon, $index),
                'regular' => $this->makeCopyableIcon("regular", $icon, $index),
                'light' => $this->makeCopyableIcon("light", $icon, $index),
                'thin' => $this->makeCopyableIcon("thin", $icon, $index),
                'duotone' => $this->makeCopyableIcon("duotone", $icon, $index, '--fa-primary-color: orange; --fa-secondary-color: green;'),
            ];
        }

        return $result;
    }
}
