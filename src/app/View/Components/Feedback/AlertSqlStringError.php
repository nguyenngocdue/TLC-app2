<?php

namespace App\View\Components\Feedback;

use Illuminate\View\Component;

class AlertSqlStringError extends Component
{
   
    public function __construct(
        private $message = "[Message] is missing.",
        private $btnHref = "",
    ) {
        //
    }

    public function render()
    {
        return view('components.feedback.alert-sql-string-error',
            [
                "message" => $this->message,
                "btnHref" => $this->btnHref,
            ]
        );
    }
}
