<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class PrintPageToc extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = [],
        private $headerDataSource = null,
        private $type = ''
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        foreach ($this->dataSource as &$value) {
            $value['response_type'] = $this->createDataSource($value);
            $tableDataSource[] = $value->toArray();
        }
        return view('components.print.print-page-toc', [
            'tableColumns' => $this->getTableColumns(),
            'tableDataSource' => $tableDataSource,
            'headerDataSource' => $this->headerDataSource,
            'type' => $this->type,
        ]);
    }
    public function getTableColumns()
    {
        return [
            [
                "title" => 'Content',
                "dataIndex" => "response_type",
                "align" => "center",
                'width' => 500,
            ],
            [
                "title" => 'Progress (%)',
                "dataIndex" => "progress",
                "align" => "center",
                'width' => 150,
            ],
            [
                "dataIndex" => "status",
                "align" => "center",
                'width' => 150,
                'renderer' => 'status',
            ],
        ];
    }
    private function createDataSource($item)
    {
        return "<a href='#{$item->slug}' class='text-blue-500'>{$item->name}</a>";
    }
}
