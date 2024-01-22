<?php

namespace App\View\Components\Print;

// use App\Http\Controllers\SignOff\Trait\TraitSupportSignOff;
use App\Models\Qaqc_insp_chklst_sht;
use Illuminate\View\Component;

class CheckSheet5 extends Component
{
    // use TraitSupportSignOff;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        // $dataSource = $this->getDataSource($this->id);
        // $tableDataSource = $this->transformDataSource($dataSource[0]);
        // return view('components.print.check-sheet5', [
        //     'tableColumns' => $this->getTableColumns(),
        //     'tableDataSource' => $tableDataSource,
        //     'headerDataSource' => $dataSource[1],
        // ]);
    }
    private function getDataSource($id)
    {
        $chklstSht = Qaqc_insp_chklst_sht::findOrFail($id);
        $runLines = $chklstSht->getLines;
        return [$runLines, $chklstSht];
    }
}
