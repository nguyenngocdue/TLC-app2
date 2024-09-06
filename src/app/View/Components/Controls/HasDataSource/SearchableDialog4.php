<?php

namespace App\View\Components\Controls\HasDataSource;

use App\Http\Controllers\Entities\ZZTraitApi\TraitSearchable;
use App\Utils\ClassList;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SearchableDialog4 extends Component
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

        private $table01Name = null,
        private $tableName = null,
        private $lineType = null,
        private $rowIndex = null,
        private $batchLength = 1,
        private $deaf = false,
    ) {
        // dump($this->selected);
        $this->selected = Arr::normalizeSelected($this->selected, old($name));
    }

    public function render()
    {
        $id = $this->name;
        // dump($id);
        $name = $this->multiple ? $this->name . "[]" : $this->name;
        $table = $this->tableName;
        $nameless = Str::modelPathFrom($table)::$nameless;

        $fieldList = static::getFieldListFromProp(SuperProps::getFor($table));
        // Log::info($fieldList);

        $model = Str::modelPathFrom($table);
        $selectedStr = $model::query()
            // ->select('id', 'name')
            ->whereIn('id', json_decode($this->selected))
            ->get()
            ->pluck($fieldList[0]['name']);
        // Log::info($selectedStr);

        $params = [
            'name' => $name,
            'id' => $id,
            'selected' => json_decode($this->selected),
            'selectedArr' => $selectedStr,
            'multipleStr' => $this->multiple ? "multiple" : "",
            'readOnlyStr' => $this->readOnly ? "readonly" : "",

            'classList' => ClassList::DROPDOWN,
            'table' => $table,
            'saveOnChange' => $this->saveOnChange,

            'lineType' => $this->lineType,
            'table01Name' => $this->table01Name,
            'rowIndex' => $this->rowIndex,
            'batchLength' => $this->batchLength,
            'nameless' => $nameless,
            'deaf' => $this->deaf,
        ];
        // dump($params);
        return view('components.controls.has-data-source.searchable-dialog4', $params);
    }
}
