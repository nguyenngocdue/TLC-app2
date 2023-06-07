<?php

namespace App\View\Components\Controls;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class Signature2 extends Component
{
    static $count = 0;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name,
        private $value = null,
        private $debug = false,
        private $updatable = true,
        private $ownerIdColumnName = 'usually_table[owner_id][index]',
    ) {
        //
        static::$count++;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $this->debug = true;
        $value_decoded = (htmlspecialchars_decode($this->value));
        return view(
            'components.controls.signature2',
            [
                'name' => $this->name,
                'value' => $this->value,
                'value_decoded' => $value_decoded,
                'count' => static::$count,
                'input_or_hidden' => $this->debug ? "text" : "hidden",
                'updatable' => $this->updatable,
                'debug' => $this->debug,
                'ownerIdColumnName' => $this->ownerIdColumnName,
                'cuid' => CurrentUser::id(),
            ]
        );
    }
}
