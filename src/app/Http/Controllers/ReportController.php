<?php

namespace App\Http\Controllers;

use App\Utils\Support\Entities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ReportController extends Controller
{
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
        dump($dataSource);
        echo "<ul>";
        foreach ($dataSource as $plural => $listOfReports) {
            echo "<li>" . Str::appTitle($plural) . "</li>";
            echo "<ul>";
            foreach ($listOfReports as $name => $path) {
                $route = route($path);
                echo "<li><a href='$route'>$name</a></li>";
            }
            echo "</ul>";
        }
        echo "</ul>";
    }
}
