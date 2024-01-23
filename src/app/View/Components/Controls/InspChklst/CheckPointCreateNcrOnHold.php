<?php

namespace App\View\Components\Controls\InspChklst;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CheckPointCreateNcrOnHold extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $line,
        private $table01Name,
        private $rowIndex,
        private $debug,
        private $checkPointIds = [],
        private $readOnly = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $isExternalInspector = CurrentUser::get()->isExternalInspector();

        $type = $this->line->getTable();
        switch ($type) {
            case 'qaqc_insp_chklst_lines':
                [$syntax, $href, $nameButton, $relatedEntities, $nameShow] = $this->getHrefCreateNCR();
                break;
            case 'hse_insp_chklst_lines':
                [$syntax, $href, $nameButton, $relatedEntities, $nameShow] = $this->getHrefHseCreateCAR();
                break;
            default:
                break;
        }
        return view('components.controls.insp-chklst.check-point-create-ncr-on-hold', [
            'line' => $this->line,
            'href' => $href,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'relatedEntities' => $relatedEntities,
            'nameButton' => $nameButton,
            'nameShow' => $nameShow,
            'syntax' => $syntax,
            'readOnly' => $this->readOnly,
            'isExternalInspector' => $isExternalInspector,
        ]);
    }
    private function getHrefCreateNCR()
    {
        $params = [
            // 'correctable_type' => Str::modelPathFrom($this->line->getTable()),
            'parent_type' => Str::modelPathFrom($this->line->getTable()),
            'parent_id' => $this->line->id,
            'description' => "During " . $this->line->description . ", ",
            'prod_discipline_id' => $this->line->getSheet->prod_discipline_id,
        ];
        if ($this->line->getProject) $params['project_id'] = $this->line->getProject->id;
        if ($this->line->getSubProject) $params['sub_project_id'] = $this->line->getSubProject->id;
        if ($this->line->getProdRouting) $params['prod_routing_id'] = $this->line->getProdRouting->id;
        if ($this->line->getProdOrder) $params['prod_order_id'] = $this->line->getProdOrder->id;
        $href = route('qaqc_ncrs.create', $params);
        $lineNcrs = $this->line->getMorphManyByIds($this->checkPointIds, 'getNcrs', false);
        return ['NCR', $href, 'Create a new NCR', $lineNcrs, 'qaqc_ncrs.show'];
    }
    private function getHrefHseCreateCAR()
    {
        $params = [
            'correctable_type' => Str::modelPathFrom($this->line->getTable()),
            'correctable_id' => $this->line->id,
        ];
        $href = route('hse_corrective_actions.create', $params);
        $lineCorrectiveActions = $this->line->getMorphManyByIds($this->checkPointIds, 'getCorrectiveActions', false);
        return ['CAR', $href, 'Create a new HSE Corrective Action', $lineCorrectiveActions, 'hse_corrective_actions.show'];
    }
}
