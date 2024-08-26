<?php

namespace App\View\Components\Renderer;

use App\Utils\ClassList;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $size = 'sm',
        private $id = '',
        private $name = '',
        private $type = "default",
        private $outline = false,
        private $htmlType = 'button',
        private $value = '',
        private $onClick = '',
        private $title = '',
        private $click = '',
        private $keydownEscape = '',
        private $accesskey = '',
        private $block = false,
        private $class = '',
        private $style = '',
        private $href = null,
        private $target = '_self',
        private $icon = null,
        private $disabled = false,
        private $badge = null,
    ) {
        // dd($this->type);
        // dump($this->size);
    }

    private function getClass($className, $block, $classList)
    {
        $block = $block ? 'block w-full' : 'inline-block';
        $defaultClass = "$className $classList $block text-{$this->size} border-2" . " ";
        switch ($this->type) {
            case "primary":
                return $defaultClass . "border-purple-600 bg-purple-600 text-white hover:bg-purple-700 hover:shadow-lg focus:bg-purple-700 focus:shadow-lg active:bg-purple-800 active:shadow-lg";
            case "secondary":
                return $defaultClass . "border-blue-600 bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg active:bg-blue-800 active:shadow-lg";
            case "success":
                return $defaultClass . "border-green-500 bg-green-500 text-white hover:bg-green-600 hover:shadow-lg focus:bg-green-600 focus:shadow-lg active:bg-green-700 active:shadow-lg";
            case "danger":
                return $defaultClass . "border-red-600 bg-red-600 text-white hover:bg-red-700 hover:shadow-lg focus:bg-red-700 focus:shadow-lg active:bg-red-800 active:shadow-lg";
            case "warning":
                return $defaultClass . "border-yellow-500 bg-yellow-500 text-white hover:bg-yellow-600 hover:shadow-lg focus:bg-yellow-600 focus:shadow-lg active:bg-yellow-700 active:shadow-lg";
            case "info":
                return $defaultClass . "border-blue-400 bg-blue-400 text-white hover:bg-blue-500 hover:shadow-lg focus:bg-blue-500 focus:shadow-lg active:bg-blue-600 active:shadow-lg";
            case "dark":
                return $defaultClass . "border-gray-800 bg-gray-800 text-white hover:bg-gray-900 hover:shadow-lg focus:bg-gray-900 focus:shadow-lg active:bg-gray-900 active:shadow-lg";
            case "link":
                return $defaultClass . "bg-transparent text-blue-600 hover:text-blue-700 hover:bg-gray-100 focus:bg-gray-100 active:bg-gray-200";
            case "refresh":
                return $defaultClass . "border-blue-600 bg-blue-600 text-white hover:bg-blue-700 hover:shadow-lg focus:bg-blue-600 focus:shadow-lg active:bg-gray-600 active:shadow-lg";
            default:
                return $defaultClass . "border-gray-200 bg-gray-200 text-gray-800 hover:bg-gray-300 hover:shadow-lg focus:bg-gray-300 focus:shadow-lg active:bg-gray-400 active:shadow-lg";
        }
    }

    private function getClassOutline($className, $block, $classList)
    {
        $block = $block ? 'block w-full' : 'inline-block';
        $defaultClass = "$className $classList $block text-{$this->size} border-2 hover:bg-black hover:bg-opacity-5 ";
        switch ($this->type) {
            case "primary":
                return $defaultClass . "border-purple-600 text-purple-600";
            case "secondary":
                return $defaultClass . "border-blue-600 text-blue-600";
            case "success":
                return $defaultClass . "border-green-500 text-green-500";
            case "danger":
                return $defaultClass . "border-red-600 text-red-600";
            case "warning":
                return $defaultClass . "border-yellow-500 text-yellow-500";
            case "info":
                return $defaultClass . "border-blue-400 text-blue-400";
            case "dark":
                return $defaultClass . "border-gray-800 text-gray-800";
            case "refresh":
                return $defaultClass . "border-gray-200 text-gray-200";
            case "link":
                return $defaultClass . "border-gray-200 text-blue-600";
            default:
                return $defaultClass . "border-gray-200 text-gray-800";
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        switch ($this->size) {
            case 'xs':
                $className = "px-1.5 py-1 ";
                break;
            default:
            case 'sm':
                $className = "px-2.5 py-2 ";
                break;
            case 'md':
                $className = "px-3 py-2.5 ";
                break;
            case 'lg':
                $className = "px-4 py-3 ";
                break;
            case 'none':
                $className = '';
                break;
        }
        $classList = ClassList::BUTTON . " disabled:opacity-50";

        $className = $this->outline ? $this->getClassOutline($className, $this->block, $classList) : $this->getClass($className, $this->block, $classList);
        $className = $this->disabled ? $className . " cursor-not-allowed " : $className;
        return view('components.renderer.button', [
            // 'label' => $this->label,
            'className' => $className . " " . $this->class,
            'style' => $this->style,
            'htmlType' => $this->htmlType,
            'value' => $this->value,
            'id' => $this->id,
            'name' => $this->name,
            'onClick' => $this->onClick,
            'title' => $this->title,
            'click' => $this->click,
            'keydownEscape' => $this->keydownEscape,
            'accesskey' => $this->accesskey,
            'href' => $this->href,
            'target' => $this->target,
            'icon' => $this->icon,
            'disabled' => $this->disabled,
            'badge' => $this->badge,
        ]);
    }
}
