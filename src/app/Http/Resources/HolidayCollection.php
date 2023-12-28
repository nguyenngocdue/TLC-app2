<?php

namespace App\Http\Resources;

use App\Models\Pj_task;
use App\Models\Public_holiday;
use App\Utils\Support\Calendar;
use App\Utils\Support\DateTimeConcern;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HolidayCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                
                return [
                    'title' => Calendar::renderTitlePublicHoliday($item),
                    'start' => $item['ph_date'],
                    'allDay' => true,
                    // 'end' => $item['ph_date'],
                    'workplace_ids' => $item['workplace_id'],
                    // 'display' => 'block',
                    'overlap' => true,
                    'color' => $this->getBackGroundColorByWorkplaceId($item['workplace_id']),
                ];
            }),
        ];
    }
    private function getBackGroundColorByWorkplaceId(array $workplaceIds){
        $backGroupColor = "#94a3b8";
        $match = Calendar::mapColor(["#5eead4","#67e8f9","#fde047","#93c5fd","#f9a8d4"]);
        if(count($workplaceIds) > 1) return $backGroupColor;
        return $match[$workplaceIds[0]]['color'] ?? $backGroupColor;
    }
}
