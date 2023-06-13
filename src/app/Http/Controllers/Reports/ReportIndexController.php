<?php

namespace App\Http\Controllers\Reports;

use App\BigThink\TraitMenuTitle;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        $result = [];

        $singular = Str::singular($tableName);
        $ucfirstName = Str::ucfirst($singular);

        $conditions = ['report' => 'Reports', 'register' => 'Registers', 'document' => 'Documents'];
        foreach ($conditions as $key => $value) {
            for ($i = 10; $i <= 100; $i += 10) {
                $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                $path = "$key-{$singular}_$mode";
                $controller = "App\\Http\\Controllers\\Reports\\$value\\{$ucfirstName}_$mode";
                $class_exists = class_exists($controller);
                if (Route::has($path) && $class_exists) {
                    $title = (new ($controller))->{$tableName}()['mode_option']; //[$mode];
                    if (isset($title[$mode])) {
                        $result[$value][$mode] = [
                            'path' => $path,
                            'title' => $title[$mode],
                        ];
                    }
                }
            }
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
