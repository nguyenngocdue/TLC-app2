<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeDueController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function getSql()
    {
        return "SELECT * FROM users WHERE id IN (1,2,3)";
    }

    public function getColumns()
    {
        return [];
    }

    public function getDataSource()
    {
        $sql = $this->getSql();
        $result = DB::select($sql);
        dump($result);
    }

    public function index(Request $request)
    {
        $dataSource = $this->getDataSource();
        $columns = $this->getColumns();
        return view("welcome-due", [
            // ''
        ]);
    }
}
