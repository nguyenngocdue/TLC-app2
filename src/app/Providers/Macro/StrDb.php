<?php

namespace App\Providers\Macro;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Act_currency_xr;
use App\Models\Project;
use App\Models\Sub_project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StrDb
{
    static $singleton = [];
    static function getNameByIdOf($modelPath, $dataSource, $key)
    {
        if (!isset(static::$singleton[$modelPath])) {
            static::$singleton[$modelPath] = $modelPath::all()->pluck('name', 'id');
            // Log::info("CACHE MISS");
        } else {
            // Log::info("CACHE HIT");
        }

        $id = $dataSource[$key];
        // dump($id);
        // dump(static::$singleton[$modelPath]);
        $result = static::$singleton[$modelPath][$id] ?? $dataSource[$key] ?? '';
        // Log::info(static::$singleton[$modelPath]);
        // Log::info($result);
        return $result;
    }
}

function getNameByIdOfExpensive($modelPath, $dataSource, $key)
{
    $result = $modelPath::find($dataSource[$key])->name ?? $dataSource[$key] ?? '';
    return $result;
}

Str::macro('markDocId', function ($dataSource, $type = null) {
    if (!$type) $type = $dataSource->getTable();
    $docId = $dataSource['doc_id'] ?? '';
    $libAppsData = LibApps::getFor($type);
    $docIdName = '';
    if ($nameColumnDocIDFormat = $libAppsData['doc_id_format_column']) {
        $organizationName = config("company.short_name");
        $entityNickName = strtoupper($libAppsData['nickname']);
        switch ($nameColumnDocIDFormat) {
            case 'project_id':
                $name = StrDb::getNameByIdOf(Project::class, $dataSource, 'project_id');
                $result = [$organizationName, $name, $entityNickName, sprintf('%04d', $docId)];
                break;
            case 'sub_project_id':
                $name = StrDb::getNameByIdOf(Sub_project::class, $dataSource, 'sub_project_id');
                $result = [$organizationName, $name, $entityNickName, sprintf('%04d', $docId)];
                break;
            case 'rate_exchange_month_id':
                $monthId = $dataSource['rate_exchange_month_id'];
                // Log::info($dataSource);
                $name = Act_currency_xr::findFromCache($monthId)->name;
                $result = [$entityNickName, $name, sprintf('%04d', $docId)];
                break;
            default:
                $name = "?$nameColumnDocIDFormat?";
                $result = [$organizationName, $name, $entityNickName, sprintf('%04d', $docId)];
                break;
        }
        $docIdName = implode('-', $result);
    }
    return $docIdName;
});
