<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Http\Controllers\Entities\ZZTraitApi\TraitSearchable;
use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SearchableDialog extends Component
{
    // use HasDataSource;
    use TraitSearchable;

    public function __construct(
        private $name,
        private $selected = null,
        private $multiple = false,
        private $type = null,
        private $readOnly = false,
        private $saveOnChange = false,
        private $allowClear = false,
        private $action = null,
    ) {
        // dump($this->selected);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    public function render()
    {
        $sp = SuperProps::getFor($this->type);
        $props = $sp['props'];
        $prop = $props["_" . $this->name];
        $letUserChooseWhenOneItem = ($prop['relationships']['let_user_choose_when_one_item'] ?? false) === "true";
        $letUserClear = ($prop['relationships']['let_user_clear'] ?? false) === "true";
        // dump($letUserChooseWhenOneItem);
        if (!isset($prop['relationships']['table'])) {
            dump("Orphan prop " . $this->type . "\\" . $this->name);
            return;
        }
        $table = $prop['relationships']['table'];
        $id = $this->name;
        $name = $this->multiple ? $this->name . "[]" : $this->name;

        $nameless = (Str::modelPathFrom($table))::$nameless;

        $fieldList = static::getFieldListFromProp(SuperProps::getFor($table));
        // Log::info($fieldList);

        $model = Str::modelPathFrom($table);
        $selectedStr = $model::query()
            // ->select('id', 'name')
            ->whereIn('id', json_decode($this->selected))
            ->get()
            ->pluck($fieldList[0]['name']);
        // Log::info($selectedStr);

        // dump($selectedStr);
        // $selectedStr = $selectedStr->map(fn($name) => Blade::render("<x-renderer.tag color='gray'>$name</x-renderer.tag>"))->join("");

        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => json_decode($this->selected),
            'selectedArr' => $selectedStr->toArray(),
            'multipleStr' => $this->multiple ? "multiple" : "",
            'readOnly' => $this->readOnly,
            'classList' => ClassList::TEXT,
            'table' => $table,
            'saveOnChange' => $this->saveOnChange,
            'allowClear' => $this->allowClear || $letUserClear,
            'nameless' => $nameless,
            'action' => $this->action,
            'letUserChooseWhenOneItem' => $letUserChooseWhenOneItem,
        ];
        // dump($params);
        return view('components.controls.has-data-source.searchable-dialog', $params);
    }
}
