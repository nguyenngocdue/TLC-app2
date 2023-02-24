<?php

namespace App\View\Components\Renderer;

use Illuminate\View\Component;

class Realtime extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $name = null,
        private $realtimeType = null,
        private $realtimeFn = null,
        private $status = null,
        private $value = null,
        private $item = null,
    ) {
        //
    }

    function getValue()
    {
        $rtType = $this->realtimeType;
        $rtFn = $this->realtimeFn;
        $className = "App\\View\\Components\\Realtime\\" . $rtFn;
        switch ($rtType) {
            case "leaving_new":
                if ($this->status === 'new' /*|| is_null($this->status)*/) {
                    return (new $className())($this->item);
                } else {
                    return $this->value;
                }
            case "never_freeze":
                return (new $className())($this->item);
            default:
                return "Unknown how to render realtime [" . $rtType . "]";
        }
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $name = $this->name;
        $value = $this->getValue();
        $classList = "bg-white border border-gray-300 text-gray-900 rounded-lg p-2.5 dark:placeholder-gray-400 block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input";
        $classList .= " readonly text-rig1ht ";
        return "<input name='$name' type='number' step='any' value='$value' readonly class='$classList'/>";
    }
}
