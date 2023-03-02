<?php

namespace App\View\Components\Renderer;

use App\Http\Controllers\Workflow\LibApps;
use App\Models\Project;
use App\Models\Sub_project;
use Illuminate\View\Component;

class DocId extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id = null;
    private $type = null;
    private $docId = null;
    public function __construct(
        private $dataLine = null,
    ) {
        //
        // dd($dataLine);
        if ($dataLine) {
            $this->type = $dataLine->getTable();
            $this->id = $dataLine->id;
            $this->docId = $dataLine->doc_id;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $libAppsData = LibApps::getFor($this->type);
        $docIdName = '';
        if ($nameColumnDocIDFormat = $libAppsData['doc_id_format_column']) {
            $organizationName = env('ORGANIZATION_NAME', 'TLC');
            $entityNickName = strtoupper($libAppsData['nickname']);
            switch ($nameColumnDocIDFormat) {
                case 'project_id':
                    $nameProjectOrSubProject = Project::find($this->id)->name ?? '';
                    break;
                case 'sub_project_id':
                    $nameProjectOrSubProject = Sub_project::find($this->id)->name ?? '';
                    break;
                default:
                    break;
            }
            $result = [
                $organizationName,
                $nameProjectOrSubProject,
                $entityNickName,
                sprintf('%04d', $this->docId)
            ];
            $docIdName = implode('-', $result);
        }
        return $docIdName;
    }
}
