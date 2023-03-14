<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ReportIndexController extends Controller
{
    public function getType()
    {
        return "reportIndex";
    }

    private function getDataSource()
    {
        $entities = Entities::getAll();
        $result = [];
        foreach ($entities as $entity) {
            $entityName = $entity->getTable();
            $singular = Str::singular($entityName);
            $ucfirstName = Str::ucfirst($singular);

            foreach (['report' => 'Reports', 'register' => 'Registers', 'document' => 'Documents'] as $key => $value) {
                for ($i = 10; $i <= 50; $i += 10) {
                    $mode = str_pad($i, 3, '0', STR_PAD_LEFT);
                    $path = "$key-{$singular}_$mode";
                    // dump($path);
                    //TODO: Thuc's PC get cache of this register and crash the app
                    if ($path === "register-hr_overtime_request_line") continue;
                    if ($path === "report-prod_routing_link") continue;
                    $controller = "App\\Http\\Controllers\\Reports\\$value\\{$ucfirstName}_$mode";
                    // dump($path);
                    // dump($controller);
                    $class_exists = class_exists($controller);
                    if (Route::has($path) && $class_exists) $result[$entityName][$value][$mode] = $path;
                }
            }
        }
        return $result;
    }

    public function index(Request $request)
    {
        $dataSource = $this->getDataSource();
        // dump($dataSource);
        return view("reports.report-index", [
            'dataSource' => $dataSource,
        ]);
    }
}
