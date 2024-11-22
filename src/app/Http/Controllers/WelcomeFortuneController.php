<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneController extends Controller
{
    function __construct(
        private WelcomeFortuneDataSource01 $dataSource01,
        private WelcomeFortuneDataSource02 $dataSource02,
        private WelcomeFortuneDataSource03 $dataSource03,
        private WelcomeFortuneDataSource04 $dataSource04,
        private WelcomeFortuneDataSource05 $dataSource05,
    ) {}

    function getType()
    {
        return "dashboard";
    }

    private function makeEditable($table)
    {
        foreach ($table['columns'] as &$column) {
            $column['mode'] = 'edit';
        }
        return $table;
    }

    public function index(Request $request)
    {
        if (sizeof($request->all())) {
            dump("Submitted table data:", $request->all());
        }
        $table01 = $this->dataSource01->getDataSource();
        $table02 = $this->dataSource02->getDataSource();
        $table03 = $this->dataSource03->getDataSource();
        $table03a = $this->makeEditable($table03);
        $table04 = $this->dataSource04->getDataSource();
        $table05 = $this->dataSource05->getDataSource();
        $table05a = $this->makeEditable($table05);

        return view("welcome-fortune", [
            'table01' => $table01,
            'table02' => $table02,
            'table03' => $table03,
            'table03a' => $table03a,
            'table04' => $table04,
            'table05' => $table05,
            'table05a' => $table05a,
        ]);
    }
}
