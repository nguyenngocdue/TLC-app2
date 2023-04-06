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

    private function getDataSource()
    {
        $entities = Entities::getAll();
        $result = [];
        $titles = [];
        foreach ($entities as $entity) {
            $entityName = $entity->getTable();
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);

            $conditions = ['report' => 'Reports', 'register' => 'Registers', 'document' => 'Documents'];
            foreach ($conditions as $key => $value) {
                for ($i = 10; $i <= 50; $i += 10) {
                    $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                    $path = "$key-{$singular}_$mode";
                    // dump($path);
                    $controller = "App\\Http\\Controllers\\Reports\\$value\\{$ucfirstName}_$mode";
                    // dump($path);
                    // dump($controller);
                    $class_exists = class_exists($controller);
                    if (Route::has($path) && $class_exists) {
                        $result[$entityName][$value][$mode] = [
                            'path' => $path,
                            'title' => (new ($controller))->{$entityName}()['mode_option'][$mode],
                        ];
                        $titles[$entityName] = LibApps::getFor($entityName)['title'];
                    }
                }
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
