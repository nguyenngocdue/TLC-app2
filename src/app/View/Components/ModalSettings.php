<?php

namespace App\View\Components;

use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class ModalSettings extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(private $type, private $title = "Settings")
    {
        // dump($this->type);
        // dump($this->title);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $path = storage_path() . "/json/entities/$this->type/props.json";
        $props = json_decode(file_get_contents($path), true);

        $allColumns = array_filter($props, fn ($prop) => isset($prop['hidden_view_all']) && $prop['hidden_view_all'] !== 'true');

        $settings = CurrentUser::getSettings();
        $selected = $settings[$this->type]['columns'] ?? [];

        return view('components.modal-settings', [
            'type' => $this->type,
            'title' => $this->title,
            'allColumns' => $allColumns,
            'selected' => $selected,
        ]);
    }
}
