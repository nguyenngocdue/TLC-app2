<?php

namespace App\View\Components\Print\InspChklst;

use App\Utils\Support\CurrentRoute;
// use Illuminate\Support\Str;
use Illuminate\View\Component;

class InspChklstToc extends Component
{
    // private $modelPath;
    private $type;

    public function __construct(
        private $entity,
        private $sheets,
    ) {
        $this->type = $entity->getTable();
        // $this->modelPath = Str::modelPathFrom($this->type);
    }

    function render()
    {
        $projectBox = [
            "Organization" => config("company.name"),
            "Project" => $this->entity->getSubProject->getProject->description,
            "Sub Project" => $this->entity->getSubProject->name,
            "Production Name" => $this->entity->getProdOrder->compliance_name,
        ];
        // dump($projectBox);

        return view("components.print.insp-chklst.insp-chklst-toc", [
            'sheets' => $this->sheets,
            'entity' => $this->entity,
            "type" => $this->type,
            "topTitle" => CurrentRoute::getTitleOf($this->type),
            "projectBox" => $projectBox,
            "templateName" => $this->entity->getQaqcInspTmpl->name,
        ]);
    }
}
