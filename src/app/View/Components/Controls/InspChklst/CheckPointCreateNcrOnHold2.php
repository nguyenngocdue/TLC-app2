<?php

namespace App\View\Components\Controls\InspChklst;

use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class CheckPointCreateNcrOnHold2 extends Component
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

    private function getHrefCreateNCR()
    {
        $params = [
            // 'correctable_type' => Str::modelPathFrom($this->line->getTable()),
            'parent_type' => Str::modelPathFrom($this->line->getTable()),
            'parent_id' => $this->line->id,
            'description' => "During ", // . $this->line->description . ", ",
            'prod_discipline_id' => $this->line->getSheet->prod_discipline_id,
        ];
        $prodOrder = $this->line->getSheet->getChklst->getProdOrder;
        if ($x = $prodOrder->getSubProject->getProject ?? null) $params['project_id'] = $x->id;
        if ($x = $prodOrder->getSubProject ?? null) $params['sub_project_id'] = $x->id;
        if ($x = $prodOrder ?? null) $params['prod_order_id'] = $x->id;
        if ($x = $prodOrder->getProdRouting ?? null) $params['prod_routing_id'] = $x->id;

        $href = route('qaqc_ncrs.create', $params);
        $lineNcrs = $this->line->getNcrs;

        return ['NCR', $href, 'Create a new NCR', $lineNcrs, 'qaqc_ncrs.show'];
    }

    private function getHrefHseCreateCAR()
    {
        $params = [
            'correctable_type' => Str::modelPathFrom($this->line->getTable()),
            'correctable_id' => $this->line->id,
        ];
        $href = route('hse_corrective_actions.create', $params);
        $lineCorrectiveActions = $this->line->getCorrectiveActions;

        return ['CAR', $href, 'Create a new HSE Corrective Action', $lineCorrectiveActions, 'hse_corrective_actions.show'];
    }

    public function render()
    {
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

        // $isExternalInspector = CurrentUser::get()->isExternalInspector();
        // $isProjectClient = CurrentUser::get()->isProjectClient();
        // $isCouncilMember = CurrentUser::get()->isCouncilMember();
        // $isExternal = $isExternalInspector || $isProjectClient || $isCouncilMember;

        $isExternal = CurrentUser::get()->isExternal();

        return view('components.controls.insp-chklst.check-point-create-ncr-on-hold2', [
            'line' => $this->line,
            'href' => $href,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'relatedEntities' => $relatedEntities,
            'nameButton' => $nameButton,
            'nameShow' => $nameShow,
            'syntax' => $syntax,
            'readOnly' => $this->readOnly,
            'isExternal' => $isExternal,
        ]);
    }
}
