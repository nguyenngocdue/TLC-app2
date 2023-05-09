<?php

namespace App\Http\Controllers;

use App\Utils\Support\DBTable;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $entities = Entities::getAll();
        // dump($entities);
        foreach ($entities as $entity) {
            // dump($entity->getTable());
            // DBTable::getColumnNames($entity);
        }
        return view("welcome-fortune", []);
    }
}
