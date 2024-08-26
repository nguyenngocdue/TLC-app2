<?php

namespace App\Http\Resources;

use App\Models\Pj_task;
use App\Models\Public_holiday;
use App\Models\Workplace;
use App\Utils\Constant;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HolidayCollection extends ResourceCollection
{
    public static function getBackGroundColorByWorkplaceId($workplaceIds, $tailwind = false)
    {
        if (!is_array($workplaceIds)) $workplaceIds = [$workplaceIds];
        $backGroupColor = $tailwind ? "slate" : "#94a3b8";
        $match = static::mapColor($tailwind ? Constant::COLOR_PUBLIC_HOLIDAY : Constant::COLOR_PUBLIC_HOLIDAY2);
        if (count($workplaceIds) > 1) return $backGroupColor;
        return $match[$workplaceIds[0]]['color'] ?? $backGroupColor;
    }
    public static function mapColor(array $colors)
    {
        $match = [];
        $workplaces = Workplace::all()->pluck('name')->toArray();
        for ($i = 0; $i < count($workplaces); $i++) {
            $match[$i + 1] = [
                "name" => $workplaces[$i],
                "color" => $colors[$i]
            ];
        }
        return $match;
    }
    private function renderTitlePublicHoliday($item)
    {
        $renderWorkplace = "";
        $match = static::mapColor(Constant::COLOR_PUBLIC_HOLIDAY);
        foreach ($item['workplace_id'] as $id) {
            $value = $match[$id] ?? "";
            if (!$value) continue;
            $renderWorkplace .= "<span class='items-center text-black rounded-lg p-1 bg-" . $value["color"] . "-500 text-xs inline-flex justify-center'>" . $value["name"] . "</span>";
        }
        $name = $item['name'] ?? "";
        return "<div class='items-center'>
                    <div>
                        <div class='text-center text-black items-center justify-center align-middle'>
                            <span class='text-sm'>$name</span>
                        </div>
                        <div class='flex gap-1 py-1 justify-center flex-wrap'>
                        $renderWorkplace
                        </div>
                    </div>
                </div>";
    }

    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {

                return [
                    'title' => $this->renderTitlePublicHoliday($item),
                    'start' => $item['ph_date'],
                    'allDay' => true,
                    // 'end' => $item['ph_date'],
                    'workplace_ids' => $item['workplace_id'],
                    // 'display' => 'block',
                    // 'overlap' => false,
                    'color' => $this->getBackGroundColorByWorkplaceId($item['workplace_id']),
                ];
            }),
        ];
    }
}
