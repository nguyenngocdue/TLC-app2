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
                $path = "$key-$singular";
                $controller = "App\\Http\\Controllers\\Reports\\$value\\{$ucfirstName}";
                $class_exists = class_exists($controller);
                if (Route::has($path) && $class_exists) $result[$entityName][$value] = $path;
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
