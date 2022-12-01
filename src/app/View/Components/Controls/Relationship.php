<?php

namespace App\View\Components\Controls;

use App\Utils\Support\Props;
use App\Utils\Support\Relationships;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Relationship extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $id, private $type, private $colName, private $tablePath, private $action, private $colSpan)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $colName = $this->colName;
        $modelPath = $this->tablePath;
        $type = $this->type;
        $id = $this->id;
        $colSpan = $this->colSpan / 2;
        $action = $this->action;

        if ($action === 'edit') {
            $itemDB = $modelPath::find($id);
            $relationship = Relationships::getAllOf($this->type);
            foreach ($relationship as $value) {
                if ($colName === $value['control_name']) {
                    $dataSource = $itemDB->$colName->all();
                    if (count($dataSource) <= 0) {
                        $message =  "There is no item to be found";
                        $type = 'warning';
                        return "<x-feedback.alert message='{{$message}}' type='{{$type}}' />";
                    };
                    switch ($value['renderer_edit_param']) {
                        case 'getManyIconParams':
                            return view('components.controls.manyIconParams')->with(compact('dataSource', 'colSpan'));
                        case 'getManyLineParams':
                            $tableColumns = [
                                ['title' => 'ID', "dataIndex" => "id", "renderer" => "id"],
                                [
                                    'title' => 'Name', "dataIndex" => "name",
                                    "renderer" => "avatar-name",
                                    "attributes" => ['title' => 'name', 'description' => 'position_rendered']
                                ],
                                ['title' => 'Position Rendered', "dataIndex" => "position_rendered"]
                            ];

                            $tableDataSource = array_map(fn ($item) => [
                                'id' => $item['id'],
                                'name' => $item['name'],
                                'position_rendered' => $item['position_rendered']
                            ], $dataSource);
                            // dump($tableDataSource);

                            return view('components.controls.manyLineParams')->with(compact('tableColumns', 'tableDataSource'));
                        default:
                            break;
                    }
                }
            }
        }
        $message =  "Only show items on the update form";
        $type = 'warning';
        return "<x-feedback.alert message='{{$message}}' type='{{$type}}' />";
    }
}
