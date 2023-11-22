<?php

namespace App\View\Components\Renderer;

use Carbon\Carbon;
use Illuminate\View\Component;

class NotificationItemRender extends Component
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
        $content = $dataSource['data']['content'] ?? '';
        $senderId = $dataSource['sender_id'] ?? '';
        $isRead = $dataSource['read_at'] ?? null;
        $timeAgo = Carbon::createFromTimestamp(strtotime($dataSource['updated_at']))->diffForHumans();
        $id = $dataSource['id'] ?? null;
        $objectType = $dataSource['object_type'] ?? null;
        $objectId = $dataSource['object_id'] ?? null;
        return view('components.renderer.notification-item-render',[
            "content" => $content,
            "senderId"  => $senderId,
            "isRead" => $isRead,
            "timeAgo" => $timeAgo,
            "id" => $id,
            "objectType" => $objectType,
            "objectId" => $objectId
        ]);
    }
}
