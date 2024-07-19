<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Team extends Component
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

    private function dataWhoUseIt()
    {
        $images = Arr::getFileListFromDisk('/images/homepage-who-use-it');
        $arrayText = [
            'Health and Safety',
            'Manufacturing',
            'Quality Control',
            'Construction Site',
            'Document Control',
            'HR and Admin',
            'Technical',
            'Finance',
            'Project Management',
            'Supply Chain',
            'Compliance',
            'Consultant',
        ];
        $results = [];
        foreach ($images as $key => $value) {
            $results[] = [
                "src" => $value,
                "name" => $arrayText[$key],
            ];
        }

        return $results;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home-web-page.team', ['dataSource' => $this->dataWhoUseIt()]);
    }
}
