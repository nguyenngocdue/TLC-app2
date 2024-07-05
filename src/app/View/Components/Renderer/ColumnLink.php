<?php

namespace App\View\Components\Renderer;

use App\Models\User;
use Illuminate\View\Component;

class ColumnLink extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataLine,
        private $column,
        private $rendererParam = '',
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if ($this->rendererParam === '') return "renderer_param ?";
        $rendererParam = $this->rendererParam;
        $result = [];

        $dataIndex = $this->column['dataIndex'];
        if (str_contains($dataIndex, "()")) {
            $dataIndex = substr($dataIndex, 0, strlen($dataIndex) - 2);
            $dataSource = $this->dataLine->{$dataIndex}();
        } else {
            $dataSource = $this->dataLine->$dataIndex;
        }
        // dump($dataIndex);

        // if (is_null($dataSource)) return;
        // $discipline = ($this->column['dataIndex'] == "getDisciplinesOfTask");
        if (!is_array($dataSource)) {
            if ($dataSource === null) $dataSource = [];
            else $dataSource = [$dataSource];
        }
        foreach ($dataSource as $item) {
            $table = $item->getTable();
            $route = route($table . ".edit", $item->id);

            $id = $item->id ?? "";
            $value = null;
            if (!isset($item->$rendererParam)) {
                // dump('l1'.$rendererParam);
                if ($rendererParam !== 'name') {
                    $result[] = "Renderer View All Param [" . $rendererParam . "] is missing";
                    continue;
                } else {
                    $value = "";
                    // $value = "Nameless #".($id); //<< This will cause eye noises
                }
            } else {
                $value = $item->$rendererParam;
                // if ($discipline) {
                //     $defAssignee = $item->def_assignee;
                //     $u = User::findFromCache($defAssignee);
                //     if ($u) $value .= " (" . $u->name . ")";
                //     else $value .= " (???????????)";
                // }
            }
            $result[] = "<a title='#{$id}' href='$route' class='hover:bg-blue-200 rounded p-1 whitespace-nowrap'>" . $value . "</a>";
        }
        return "<p class='p-2'>" . join(", ", $result) . "</p>";
        // dump($this->column);
        // dump($this->dataLine);
        // dump($this->cell);
        // return function (array $data) {

        //     $rendererParam = $this->rendererParam;

        //     $result = [];
        //     $json = json_decode($data['slot']);
        //     if (!is_array($json)) $json = [$json];
        //     foreach ($json as $item) {
        //         $id = $item->id ?? "";
        //         $value = null;
        //         if (!isset($item->$rendererParam)) {
        //             // dump('l1'.$rendererParam);
        //             if ($rendererParam !== 'name') {
        //                 $result[] = "Renderer View All Param [" . $rendererParam . "] is missing";
        //                 continue;
        //             } else {
        //                 $value = "";
        //                 // $value = "Nameless #".($id); //<< This will cause eye noises
        //             }
        //         } else {
        //             $value = $item->$rendererParam;
        //         }
        //         dump($item);
        //         $result[] = "<span title='#{$id}'>" . $value . "</span>";
        //     }
        //     echo "<p class='p-2'>" . join(", ", $result) . "</p>";
        // };
    }
}
