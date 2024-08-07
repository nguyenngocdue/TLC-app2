<?php

namespace App\View\Components\Controls\InspChklst;

use App\Models\Control_type;
use App\Models\Qaqc_insp_chklst_line;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;

class CheckPoint extends Component
{
    public function __construct(
        private $line,
        private $type,
        private $table01Name,
        private $rowIndex,
        private $debug = false,
        private $checkPointIds = [],
        private $sheet = null,
        private $readOnly = false,
        private $index = 0,
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
        $controlType = Control_type::getCollection()->pluck('name', 'id',) ?? Control_type::get()->pluck('name', 'id');
        $attachments = $this->line->getMorphManyByIds($this->checkPointIds, 'insp_photos'); // cache attachments
        $props = SuperProps::getFor(Qaqc_insp_chklst_line::getTableName());
        $destroyable = !CurrentUser::get()->isExternal();
        $prodOrder = $this->sheet->getChklst->getProdOrder;
        $meta_type = $prodOrder->meta_type;
        $meta_id = $prodOrder->meta_id;
        $module = $meta_type::findFromCache($meta_id, ['getPjType']);
        // dump($module->name);
        $module_type = $module->getPjType;
        $roomList = $module_type->getRoomList;

        $isAttachmentGrouped = $this->sheet->getTmplSheet->is_attachment_grouped;
        $groups = null;
        if ($isAttachmentGrouped) {
            $groups = $roomList->pluck('name', 'id');
            // dump($roomList);
        }
        return view('components.controls.insp-chklst.check-point', [
            'line' => $this->line,
            'controlType' => $controlType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'attachments' => $attachments,
            'checkPointIds' => $this->checkPointIds,
            'debug' => $this->debug,
            'type' => $this->type,
            'props' => $props,
            'readOnly' => $this->readOnly,
            'index' => $this->index,
            'destroyable' => $destroyable,
            'groups' => $groups,
        ]);
    }
}
