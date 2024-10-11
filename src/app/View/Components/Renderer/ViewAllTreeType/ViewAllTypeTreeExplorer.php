<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

abstract class ViewAllTypeTreeExplorer extends Component
{
    abstract protected function getTree();
    abstract protected function getApiRoute();
    protected $showSearch = false;

    function __construct(
        private $type = null,
    ) {}

    function render()
    {
        return view('components.renderer.view-all.view-all-type-tree-explorer', [
            'tree' => $this->getTree(),
            'route' => $this->getApiRoute(),
            'type' => $this->type,
            'createNewShortRoute' => Route::has($this->type . ".createNewShort") ? route($this->type . ".createNewShort") : null,
            'updateRoute' => Route::has($this->type . ".updateShortSingle") ? route($this->type . ".updateShortSingle") : null,
            'ownerId' => CurrentUser::id(),
            'showSearch' => $this->showSearch,
        ]);
    }
}
