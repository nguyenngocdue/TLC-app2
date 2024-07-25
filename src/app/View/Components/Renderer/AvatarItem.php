<?php

namespace App\View\Components\Renderer;

use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class AvatarItem extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $title = "No Name",
        private $description = "",
        private $avatar = "",
        private $href = "",
        private $gray = false,
        private $verticalLayout = false,
        private $tooltip = '',
        private $flipped = false,
        private $class = null,
        private $shape = null,
        private $size = null,
        private $content = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (is_numeric($this->size)) {
            $sizeStr = "w-$this->size h-$this->size";
        } else switch ($this->size) {
            case 'small':
                $sizeStr = "w-6 h-6";
                break;
            case 'large':
                $sizeStr = "w-10 h-10";
                break;
            case 'xl':
                $sizeStr = "w-14 h-14";
                break;
            case null:
                $sizeStr = "w-8 h-8";
                break;
            default:
                $sizeStr = $this->size;
                break;
        }
        return view('components.renderer.avatar-item', [
            'title' => $this->title,
            'description' => $this->description,
            'avatar' => $this->avatar,
            'href' => $this->href,
            'gray' => $this->gray,
            'verticalLayout' => $this->verticalLayout,
            'tooltip' => $this->tooltip,
            'flipped' => $this->flipped,
            'class' => $this->class,
            'sizeStr' => $sizeStr,
            'shape' => $this->shape == 'square' ? "rounded" : "rounded-full",
            'content' => $this->content,
        ]);
    }
}
