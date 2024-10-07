<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

abstract class ViewAllTypeTreeExplorer extends Component
{
    abstract protected function getTree();
    abstract protected function getApiRoute();

    function __construct(
        private $type = null,
    ) {}

    function render()
    {
        return view('components.renderer.view-all.view-all-type-tree-explorer', [
            'tree' => $this->getTree(),
            'route' => $this->getApiRoute(),
            'type' => $this->type,
            'createNewShortRoute' =>  route($this->type . ".createNewShort"),
            'updateRoute' => route($this->type . ".updateShortSingle"),
            'ownerId' => CurrentUser::id(),
        ]);
    }
}
