<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use Illuminate\View\Component;

abstract class ViewAllTypeTreeExplorer extends Component
{
    abstract protected function getTree();
    abstract protected function getApiRoute();

    function render()
    {
        return view('components.renderer.view-all.view-all-type-tree-explorer', [
            'tree' => $this->getTree(),
            'route' => $this->getApiRoute(),
        ]);
    }
}
