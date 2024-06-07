<?php

namespace App\View\Components\Renderer\Kanban;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityListenDataSource;
use App\Http\Controllers\Entities\ZZTraitEntity\TraitSupportEntityCRUDCreateEdit2;
use App\Providers\Support\TraitSupportPermissionGate;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class ItemRendererModal extends Component
{
    use TraitSupportEntityCRUDCreateEdit2;
    use TraitEntityListenDataSource;
    use TraitSupportPermissionGate;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $id = null,
        private $type = null,
        private $item = null,
        private $props = null,
        private $modelPath = null,
    ) {
        // Log::info($this->props);
        // Log::info($this->item);
        // Log::info($this->type);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tableBluePrint = $this->makeTableBluePrint($this->props);
        $tableToLoadDataSource = [...array_values($tableBluePrint), $this->type];
        $listenerDataSource = $this->renderListenDataSource($tableToLoadDataSource);

        // $original = $this->checkPermissionUsingGate($this->id, 'edit');
        $original = $this->modelPath::find($this->id);
        $values = (object) $this->loadValueOfBelongsToManyAndAttachments($original, $this->props);

        // dump($values);
        // Log::info($tableBluePrint);
        // Log::info($tableToLoadDataSource);
        // Log::info($listenerDataSource);

        $listeners2 = $this->getListeners2($this->type);
        $filters2 = $this->getFilters2($this->type);
        $listeners4 = $this->getListeners4($tableBluePrint);
        $filters4 = $this->getFilters4($tableBluePrint);

        // dump($listenerDataSource);

        $params =  [
            'listenerDataSource' => $listenerDataSource,
            'listeners2' => $listeners2,
            'filters2' => $filters2,
            'listeners4' => $listeners4,
            'filters4' => $filters4,

            'props' => $this->props,
            'item' => $this->item,
            'values' => $values,
        ];
        return view('components.renderer.kanban.item-renderer-modal', $params);
    }
}
