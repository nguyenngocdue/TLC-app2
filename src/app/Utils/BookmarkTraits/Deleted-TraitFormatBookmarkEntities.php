<?php

namespace App\Utils\BookmarkTraits;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\AccessLogger\EntityNameClickCount;
use App\Utils\AccessLogger\LoggerAccessRecent;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;

trait TraitFormatBookmarkEntities
{
    public function getDataSource($allApps)
    {
        $data = (new EntityNameClickCount)(CurrentUser::id());
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
            $entitiesName = array_filter($entitiesName, fn ($item) => $item);
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
            $packageTabRender = $value['package_tab'] ?? '';
            $packageRender = $value['package_rendered'];
            $subPackageRender = $value['sub_package_rendered'];
            $array[$packageTabRender][$packageRender]['items'][$subPackageRender]['items'][$key] = $value;
            if (isset($array[$packageTabRender][$packageRender]['total'])) {
                $array[$packageTabRender][$packageRender]['total'] += $clickCount;
            } else {
                $array[$packageTabRender][$packageRender]['total'] = $clickCount;
            }
            if (isset($array[$packageTabRender][$packageRender]['items'][$subPackageRender]['total'])) {
                $array[$packageTabRender][$packageRender]['items'][$subPackageRender]['total'] += $clickCount;
            } else {
                $array[$packageTabRender][$packageRender]['items'][$subPackageRender]['total'] = $clickCount;
            }
        }
        usort($array, function ($a, $b) {
            usort($a, function ($c, $d) {
                return $c['total'] - $d['total'];
            });
            usort($b, function ($c, $d) {
                return $c['total'] - $d['total'];
            });
        });
        $result = [];
        foreach ($array as $value) {
            foreach ($value as $key => $value) {
                foreach ($value['items'] as $key => $value2) {
                    $result[] = $value2['items'];
                }
            }
        }
        $result = array_reduce($result, function ($carry, $item) {
            return array_merge($carry, $item);
        }, []);
        return $result;
    }
    private function getDataSourceRecentDoc()
    {
        $data = (new LoggerAccessRecent)(CurrentUser::id());
        // Log::info($data);
        return collect($data)->toArray();
    }
    private function filterRecentDocument($array, $allApps)
    {
        return array_filter(array_map(function ($item) use ($allApps) {
            $index = $item->entity_name;
            if (isset($allApps[$index])) {
                $allApps[$index]['href_recent'] = $item->url;
                $allApps[$index]['action_recent'] = substr($item->route_name, strpos($item->route_name, ".") + 1);
                $allApps[$index]['entity_id'] = $item->entity_id;
                return $allApps[$index];
            }
        }, $array), fn ($item) => $item);
    }
    public function getAllAppsOfSearchModalAndTopDrawer()
    {
        $recentDoc = $this->getDataSourceRecentDoc();
        $allApps = $this->getDataSource(LibApps::getAllShowBookmark());
        $allAppsTopDrawer = $this->getDataSource(LibApps::getAllNavbarBookmark());
        $allAppsTopDrawer = $this->formatDataSource($allAppsTopDrawer);
        $allAppsRecent = $this->filterRecentDocument($recentDoc, $allApps);
        return [array_values($allAppsRecent), array_values($allApps), array_values($allAppsTopDrawer)];
    }
}
