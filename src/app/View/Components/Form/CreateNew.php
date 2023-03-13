<?php

namespace App\View\Components\Form;

use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class CreateNew extends Component
{
    private $columns, $dataSource;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $action = "",
        private $method = "GET",
        private $footer = "Pipe is allowed. E.G.: name1|name2|name3|...",
    ) {
        // dd("Create New");
        $this->columns = [
            [
                'title' => 'Name',
                'dataIndex' => 'name',
                'renderer' => 'text4',
                'editable' => true,
            ],
            [
                'title' => 'Action',
                'dataIndex' => 'action',
                'renderer' => 'button',
                'align' => 'center',
                'type' => 'primary',
                'properties' => ['htmlType'  => 'submit'],
            ],
        ];
        $this->dataSource = [
            [
                'name' => '',
                'action' => 'Create',
            ]
        ];
    }

    private function getMethod0()
    {
        switch (Str::upper($this->method)) {
            case "PUT":
            case "DELETE":
            case "POST":
                return "POST";
            default:
                return "GET";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form.create-new', [
            'action' => $this->action,
            'method' => $this->method,
            'method0' => $this->getMethod0(),
            'footer' => $this->footer,
            'columns' => $this->columns,
            'dataSource' => $this->dataSource,
        ]);
    }
}
