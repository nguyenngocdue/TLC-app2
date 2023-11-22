<?php

namespace App\View\Components\Renderer\Notification;

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
        $message = $dataSource['data']['message'] ?? '';
        $senderId = $dataSource['sender_id'] ?? '';
        $isRead = $dataSource['read_at'] ?? null;
        $timeAgo = Carbon::createFromTimestamp(strtotime($dataSource['updated_at']))->diffForHumans();
        $id = $dataSource['id'] ?? null;
        $objectType = $dataSource['object_type'] ?? null;
        $objectId = $dataSource['object_id'] ?? null;
        $scrollTo = $dataSource['scroll_to'] ?? null;
        return view('components.renderer.notification.notification-item-render', [
            "message" => $message,
            "senderId"  => $senderId,
            "isRead" => $isRead,
            "timeAgo" => $timeAgo,
            "id" => $id,
            "objectType" => $objectType,
            "scrollTo" => $scrollTo,
            "objectId" => $objectId,
        ]);
    }
}
