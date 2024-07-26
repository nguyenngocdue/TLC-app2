<?php

namespace App\View\Components\Print;

use Illuminate\View\Component;

class CoverPage extends Component
{
    function __construct(
        private $headerDataSource,
    ) {
        // dump($this->headerDataSource);
    }

    function render()
    {
        $checklist = $this->headerDataSource;
        $template = $checklist->getQaqcInspTmpl;
        $routing = $checklist->getProdRouting;
        $prodOrder = $checklist->getProdOrder;
        $subProject = $this->headerDataSource->getProdOrder->getSubProject;
        $project = $subProject->getProject;
        $src = app()->pathMinio() . $project->getAvatar->url_media;
        $params = [
            'src' => $src,
            'project' => $project,
            'subProject' => $subProject,
            'routing' => $routing,
            'prodOrder' => $prodOrder,
            'dataSource' => $this->headerDataSource,
            'template' => $template,
        ];
        // dump($params);
        return view('components.print.cover-page', $params);
    }
}
