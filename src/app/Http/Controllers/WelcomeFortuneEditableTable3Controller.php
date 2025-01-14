<?php

namespace App\Http\Controllers;

use App\Models\Qaqc_insp_chklst_line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneEditableTable3Controller extends Controller
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

    public function store(Request $request)
    {
        dump("In STORE");
        return $this->index($request);
    }

    public function index(Request $request)
    {

        // $line = Qaqc_insp_chklst_line::find(214121);
        // $line->update(['value' => 'new value']);
        // dd("DONE");

        if (sizeof($request->all())) {
            dump("Submitted table data:", $request->input());
        }
        $table01 = $this->dataSource01->getDataSource();
        $table02 = $this->dataSource02->getDataSource();
        $table03 = $this->dataSource03->getDataSource();
        $table03a = $this->makeEditable($table03);
        $table04 = $this->dataSource04->getDataSource();
        $table05 = $this->dataSource05->getDataSource();
        $table05a = $this->makeEditable($table05);

        return view("welcome-fortune-editable-table3", [
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
