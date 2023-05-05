<?php

namespace App\View\Components\Renderer;

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

        $dataIndex = $this->column['dataIndex'];
        $dataSource = $this->dataLine->$dataIndex;
        // dump($dataSource);
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
            }
            $result[] = "<a title='#{$id}' href='$route'>" . $value . "</a>";
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
