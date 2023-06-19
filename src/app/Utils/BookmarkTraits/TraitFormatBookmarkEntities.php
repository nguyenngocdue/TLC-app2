<?php

namespace App\Utils\BookmarkTraits;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityNameClickCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait TraitFormatBookmarkEntities
{
    public function getDataSource($allApps)
    {
        $data = (new EntityNameClickCount)(Auth::id());
        $entitiesName = collect($data)->pluck('entity_name')->toArray();
        $entities = $this->convertData($data);
        // dd($entities);
        // Log::info($allApps);
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
    private function formatDataSource($allApps)
    {
        $array = [];
        foreach ($allApps as $key => $value) {
            $clickCount = $value['click_count'] ?? 0;
            $packageRender = $value['package_rendered'];
            $subPackageRender = $value['sub_package_rendered'];
            $array[$packageRender]['items'][$subPackageRender]['items'][$key] = $value;
            if (isset($array[$packageRender]['total'])) {
                $array[$packageRender]['total'] += $clickCount;
            } else {
                $array[$packageRender]['total'] = $clickCount;
            }
            if (isset($array[$packageRender]['items'][$subPackageRender]['total'])) {
                $array[$packageRender]['items'][$subPackageRender]['total'] += $clickCount;
            } else {
                $array[$packageRender]['items'][$subPackageRender]['total'] = $clickCount;
            }
        }
        usort($array, function ($a, $b) {
            return $b['total'] - $a['total'];
        });
        $result = [];
        foreach ($array as $key => $value) {
            foreach ($value['items'] as $key => $value2) {
                $result[] = $value2['items'];
            }
        }
        $result = array_reduce($result, function ($carry, $item) {
            return array_merge($carry, $item);
        }, []);
        return $result;
    }
    public function getAllAppsOfSearchModalAndTopDrawer()
    {
        $allApps = $this->getDataSource(LibApps::getAllShowBookmark());
        $allAppsTopDrawer = $this->getDataSource(LibApps::getAllNavbarBookmark());
        $allAppsTopDrawer = $this->formatDataSource($allAppsTopDrawer);
        return [array_values($allApps), array_values($allAppsTopDrawer)];
    }
}
