<?php

namespace App\View\Components\Homepage;

use App\Models\Project;
use Illuminate\View\Component;

class MenuProjectMfAndCs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
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
        $allProjectMFandCS = Project::getAllProjectByCondition()->map(function ($item) {
            $href = route('projects.show', $item->id) ?? '';
            $item['href'] = $href;
            return $item;
        });
        return view('components.homepage.menu-project-mf-and-cs', [
            'projects' => $allProjectMFandCS,
        ]);
    }
}
