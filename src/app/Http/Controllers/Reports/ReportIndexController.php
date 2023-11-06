<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibReports;
use App\Utils\Support\Entities;
use App\Utils\Support\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportIndexController extends Controller
{
    use TraitMenuTitle;

    public function getType()
    {
        return "reportIndex";
    }

    public static function getReportOf($tableName)
    {
        $lib = LibReports::getAll();
        $reports = Report::getAllRoutes();
        $singular = Str::singular($tableName);
        $routes = array_filter($reports, fn ($i) => $i['singular'] == $singular);
        $result = [];
        foreach ($routes as $route) {
            $itemConfig = $lib[$route['name']];

            if ($itemConfig['hidden'] ?? false) continue;
            $result[$route['reportType']][$route['mode']] = [
                "path" => $route['name'],
                "title" => $itemConfig['title'] ?? "Untitled Report",
                "breadcrumbGroup" => $itemConfig["breadcrumb-group"],
            ];
        }
        return $result;
    }

    public static function getDataSource()
    {
        $entities = Entities::getAll();
        $result = [];
        $titles = [];
        foreach ($entities as $entity) {
            $tableName = $entity->getTable();
            $array = static::getReportOf($tableName);
            // dump($array, $title);
            if (!empty($array)) {
                $result[$tableName] = $array;
                $titles[$tableName] = LibApps::getFor($tableName)['title'];;;
            }
        }
        // dump($result);
        return [$result, $titles];
    }

    public function index(Request $request)
    {
        [$dataSource, $titles] = $this->getDataSource();
        // dump($dataSource);
        return view("reports.report-index", [
            'dataSource' => $dataSource,
            'titles' => $titles,
        ]);
    }
}
