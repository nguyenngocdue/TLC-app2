<?php

namespace App\Utils\BookmarkTraits;

use App\Utils\AccessLogger\EntityNameClickCount;
use Illuminate\Support\Facades\Auth;

trait TraitFormatBookmarkEntities
{
    public function getDataSource($allApps)
    {
        $data = (new EntityNameClickCount)(Auth::id());
        $entitiesName = collect($data)->pluck('entity_name')->toArray();
        $entities = $this->convertData($data);
        if (!empty($data)) {
            array_walk($allApps, function (&$value, $key) use ($entities) {
                if (($entities[$key] ?? null) && app()->present()) {
                    $value['click_count'] = $entities[$key]['click_count'];
                }
            });
            $allApps = array_filter(array_replace(array_flip($entitiesName), $allApps), function ($item) {
                return is_array($item);
            });
        }
        return $allApps;
    }
    private function convertData($data)
    {
        $result = [];
        array_map(function ($item) use (&$result) {
            $result[$item->entity_name] = ['entity_name' => $item->entity_name, 'click_count' => $item->click_count];
        }, $data);
        return $result;
    }
}
