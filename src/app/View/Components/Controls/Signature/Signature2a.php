<?php

namespace App\View\Components\Controls\Signature;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class Signature2a extends Component
{
    static $count = 0; // To make the ID of each signature pad unique on form.
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $readOnly = false,
        private $debug = !true,
        private $value = null,

        private $showCommentBox = false,
        private $comment = '',
        private $commentName = '',
        // private $signatureUserId = null,
    ) {
        static::$count++;
        $this->debug = !!$debug;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // dump("Debug: " . $this->debug);
        // dump("ReadOnly: " . $this->readOnly);
        // dump("Count: " . static::$count);
        $value_decoded = (htmlspecialchars_decode($this->value));
        // $mine_signature = CurrentUser::id() == $this->signatureUserId;

        $readOnly = $this->readOnly; //|| (!$mine_signature);
        // $id = str_replace(['[', ']'], "_", $this->name);
        return view(
            'components.controls.signature.signature2a',
            [
                // 'id' => $id,
                'id' => $this->name,
                'name' => $this->name,
                'count' => static::$count,
                'readOnly' => $readOnly ? 1 : 0,
                'debug' => $this->debug,
                'input_or_hidden' => $this->debug ? "text" : "hidden",
                'value' => $this->value,
                'comment' => $this->comment,
                'value_decoded' => $value_decoded,
                'showCommentBox' => $this->showCommentBox,
                'commentName' => $this->commentName,
            ]
        );
    }
}
