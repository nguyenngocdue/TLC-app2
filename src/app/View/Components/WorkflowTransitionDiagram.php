<?php

namespace App\View\Components;

use App\Http\Controllers\Workflow\LibStatuses;
use Illuminate\View\Component;

class WorkflowTransitionDiagram extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $dataSource = [],
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
        $blocks = [];
        $allStatus = LibStatuses::getAllWithHex();
        // dump($allStatus);
        // dump($this->dataSource);
        foreach ($this->dataSource as $index => $value) {
            $statusKey = $value['name'];
            $status = $allStatus[$statusKey];
            $block = [
                'key' => $statusKey,
                'title' => $status['title'],
                'color' => $status['bg_color_hex'] ?? "red",
                'bg-color' => $status['text_color_hex'] ?? "red",
                'index' => $index,
            ];
            if (isset($value['location'])) {
                $block['location'] = $value['location'];
            }
            $blocks[] = $block;
        }
        // dump($blocks);
        $links = [];
        foreach ($this->dataSource as $value0) {
            foreach ($value0 as $key => $value) {
                if ($key == 'name') continue;
                if ($value == 'true') {
                    $links[] = [
                        'from' => $value0['name'],
                        'to' => $key,
                    ];
                }
            }
        }
        // dump($links, $blocks);
        return view(
            'components.workflow-transition-diagram',
            [
                'blocks' => $blocks,
                'links' => $links,
            ]
        );
    }
}
