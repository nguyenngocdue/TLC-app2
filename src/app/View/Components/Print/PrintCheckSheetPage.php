<?php

namespace App\View\Components\Print;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class PrintCheckSheetPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = null,
        private $layout = null,
        private $headerDataSource = null,
        private $tableColumns = null,
        private $tableDataSource = null,
        private $nominatedListFn = null,
    ) {
        //
        // dump("Say something");
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $isExternalInspector = CurrentUser::get()->isExternalInspector();
        if ($isExternalInspector) {
            $nominatedList = $this->headerDataSource->{$this->nominatedListFn . "_list"}()->pluck('id');
            if (!$nominatedList->contains(CurrentUser::id())) {
                return "<x-feedback.result type='warning' title='Permission Denied' message='You are not permitted to view this check sheet.<br/>If you believe this is a mistake, please contact our admin.' />";
            }
        }

        return view('components.print.print-check-sheet-page', [
            'type' => $this->type,
            'layout' => $this->layout,
            'headerDataSource' => $this->headerDataSource,
            'tableColumns' => $this->tableColumns,
            'tableDataSource' => $this->tableDataSource,
        ]);
    }
}
