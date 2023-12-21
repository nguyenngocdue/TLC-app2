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
                    'color' => '#94a3b8',
                    'start' => $item['ph_date'],
                    'end' => $item['ph_date'],
                    'workplace_ids' => $item['workplace_id']
                ];
            }),
        ];
    }
}
