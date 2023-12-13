<?php

namespace App\View\Components\Feedback;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $type = "info",
        private $title = null,
        private $message = "[Message] is missing.",
        private $class = "",
        private $titleless = false,
    ) {
        //
    }

    static function getClass($type)
    {
        switch ($type) {
            case "success":
                return "text-green-700 border-green-300 bg-green-50 dark:bg-green-200";
            case "warning":
                return "text-yellow-700 border-yellow-300 bg-yellow-50 dark:bg-yellow-200";
            case "error":
                return "text-red-700 border-red-300 bg-red-50 dark:bg-red-200";
            case "info":
            default:
                return "text-blue-700 border-blue-300 bg-blue-50 dark:bg-blue-200";
        }
    }

    static function getIcon($type)
    {
        switch ($type) {
            case "success":
                return "fa-duotone fa-circle-check";
            case "warning":
                return "fa-duotone fa-circle-exclamation";
            case "error":
                return "fa-duotone fa-circle-xmark";
            case "info":
            default:
                return "fa-duotone fa-circle-info";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $type = $this->type;
        $title = $this->title ?? $this->type;
        $message = $this->message;
        // $class = $this->class;
        $class = static::getClass($type);
        $svg = static::getIcon($type);
        // switch ($type) {
        //     case "success":
        //         // $class = "text-green-700 border-green-300 bg-green-50 dark:bg-green-200";
        //         $svg = "fa-duotone fa-circle-check";
        //         break;
        //     case "warning":
        //         // $class = "text-yellow-700 border-yellow-300 bg-yellow-50 dark:bg-yellow-200";
        //         $svg = "fa-duotone fa-circle-exclamation";
        //         break;
        //     case "error":
        //         // $class = "text-red-700 border-red-300 bg-red-50 dark:bg-red-200";
        //         $svg = "fa-duotone fa-circle-xmark";
        //         break;
        //     case "info":
        //     default:
        //         $type = $title = "info";
        //         // $class = "text-blue-700 border-blue-300 bg-blue-50 dark:bg-blue-200";
        //         $svg = "fa-duotone fa-circle-info";
        //         break;
        // }
        $title = ucfirst($title);
        $titleless = $this->titleless;
        return view('components.feedback.alert')->with(compact('type', 'title', 'class', 'svg', 'message', 'titleless'));
    }
}
