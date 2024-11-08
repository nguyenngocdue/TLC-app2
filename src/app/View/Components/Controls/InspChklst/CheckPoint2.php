<?php

namespace App\View\Components\Controls\InspChklst;

use App\Http\Controllers\Workflow\LibDashboards;
use App\Http\Services\LoadManyCheckpointService;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\Properties;
use Illuminate\View\Component;

class CheckPoint2 extends Component
{
    public function __construct(
        private $line,
        private $type,
        private $table01Name,
        private $rowIndex,
        private $debug = false,
        private $checkPointIds = [],
        // private $sheet = null,
        private $readOnly = false,
        private $index = 0,
        private $categoryName = "",
    ) {
        //
        // dump($readOnly);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $destroyable = !CurrentUser::get()->isExternal();
        $sheet = $this->line->getSheet;
        $groups = LoadManyCheckpointService::getAttachmentGroups($sheet);
        $dashboardConfig = LibDashboards::getAll()[CurrentUser::get()->discipline] ?? null;

        $checkPointReadOnly = $allowToUpload = $allowToComment = $this->readOnly;
        if ($dashboardConfig) {
            $checkPointReadOnly = $this->readOnly || !isset($dashboardConfig['be_able_to_change_checkpoint']);
            $allowToUpload = $this->readOnly || isset($dashboardConfig['be_able_to_upload_photo_checkpoint']);
            $allowToComment = $this->readOnly || isset($dashboardConfig['be_able_to_comment_checkpoint']);
        }

        return view('components.controls.insp-chklst.check-point2', [
            'line' => $this->line,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'attachments' => $this->line->insp_photos,
            'checkPointIds' => $this->checkPointIds,
            'debug' => $this->debug,
            'type' => $this->type,
            'attachmentProperties' => Properties::getFor('attachment', "_insp_photos"),
            'readOnly' => $checkPointReadOnly,
            'index' => $this->index,
            'destroyable' => $destroyable,
            'groups' => $groups,
            'categoryName' => $this->categoryName,
            'allowToUpload' => $allowToUpload,
            'allowToComment' => $allowToComment,
        ]);
    }
}
