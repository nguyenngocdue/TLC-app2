<?php

namespace App\View\Components\Social;

use Illuminate\View\Component;

class ButtonIcon extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
    private $width = 'w-full',
    private $src=false,
    private $icon= false, 
    private $textColor = 'text-gray-900',
    private $widthIcon = 'w-10',
    private $heightIcon = 'h-10',
    private $title= '',
    private $onClickModal = false,
    private $onKeyDown = false,
    private $modalId = '',
    )
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
        $params = [
            'width' => $this->width,
            'src' => $this->src,
            'icon' => $this->icon,
            'textColor' => $this->textColor,
            'widthIcon' => $this->widthIcon,
            'heightIcon' => $this->heightIcon,
            'title' => $this->title,
            'onClickModal' => $this->onClickModal,
            'onKeyDown' => $this->onKeyDown,
            'modalId' => $this->modalId,
        ];
        return view('components.social.button-icon',$params);
    }
}
