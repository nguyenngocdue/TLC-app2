<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Http\Controllers\Workflow\LibStatuses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

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
        $idNotification = $dataSource['id'];
        $status = $dataModel['status'] ?? '';
        $title = $dataModel['name'] ?? 'NameLess' . $id;
        $isRead = $dataSource['read_at'];
        $timeAgo = Carbon::createFromTimestamp(strtotime($dataSource['updated_at']))->diffForHumans();
        $dataStatus = LibStatuses::getFor($typeEntity)[$status] ?? [];
        $titleStatus = $dataStatus['title'] ?? '';
        $colorStatus = $dataStatus['color'] ?? '';
        $colorIndexStatus = $dataStatus['color_index'] ?? '';

        return view('components.renderer.notification-item', [
            'documentType' => LibApps::getFor($typeEntity)['title'],
            'type' => $typeEntity,
            'id' => $id,
            'idNotification' => $idNotification,
            'titleStatus' => $titleStatus,
            'colorStatus' => $colorStatus,
            'colorIndexStatus' => $colorIndexStatus,
            'title' => $title,
            'isRead' => $isRead,
            'timeAgo' => $timeAgo,
        ]);
    }
}
