<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibApps;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class NotificationItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $dataSource)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataSource;
        $dataModel = $dataSource['data']['currentValue'];
        $typeEntity = $dataModel['entity_type'];
        $id = $dataModel['id'];
        $status = $dataModel['status'] ?? '';
        $typePlural = Str::plural($typeEntity);
        $routeName = "{$typePlural}.edit";
        $routeExits =  (Route::has($routeName));
        $href =  $routeExits ? route($routeName, $id) : "#";
        $title = $dataModel['name'] ?? 'NameLess' . $id;
        return view('components.renderer.notification-item', [
            'documentType' => LibApps::getFor($typeEntity)['title'],
            'href' => $href,
            'status' => $status,
            'title' => $title,
        ]);
    }
}
