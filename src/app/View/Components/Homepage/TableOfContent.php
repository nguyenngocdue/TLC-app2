<?php

namespace App\View\Components\Homepage;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitGetGroupChkSht;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\SuperProps;
use Illuminate\View\Component;
use Illuminate\Support\Str;

class TableOfContent extends Component
{
    use TraitGetGroupChkSht;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $item = null,
        private $type = null,
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
        $typeEditRenderer =  LibApps::getFor($this->type)['edit_renderer'] ?? null;
        $props = SuperProps::getFor($this->type)['props'];
        $a = array_filter($props, function ($item) {
            return $item['column_type'] == 'static' && $item['control'] != 'z_divider';
        });
        $results = [];
        foreach ($a as $value) {
            $control = $value['control'];
            switch ($control) {
                case 'z_h1':
                    $results[] = ['value' => $value['label'], 'index' => 1];
                    break;
                case 'z_h2':
                    $results[] = ['value' => $value['label'], 'index' => 2];
                    break;
                case 'z_h3':
                    $results[] = ['value' => $value['label'], 'index' => 3];
                    break;
                case 'z_h4':
                    $results[] = ['value' => $value['label'], 'index' => 4];
                    break;
                case 'z_h5':
                    $results[] = ['value' => $value['label'], 'index' => 5];
                    break;
                case 'z_h6':
                    $results[] = ['value' => $value['label'], 'index' => 6];
                    break;
                default:
                    # code...
                    break;
            }
            # code...
        }

        if (in_array($this->type, ["qaqc_insp_chklst_shts", "hse_insp_chklst_shts"])) {
            $lines = $this->item->getLines;
            [$groupColumn, $groupNames] = $this->getGroups($lines);
            $groupLines = $lines->groupBy($groupColumn);
            return $this->renderTableOfContentCheckList($groupLines, $groupColumn, $groupNames);
        }
        switch ($typeEditRenderer) {
            case '': //props-renderer
                return $this->renderTableOfContentProps($results);
                break;
            case 'report-renderer':
                return $this->renderTableOfContentReport($results);
                break;
            default:
                # code...
                break;
        }
        return '';
    }
    private function getMinIndex($array)
    {
        $minIndex = PHP_INT_MAX;
        foreach ($array as $item) {
            if (isset($item['index']) && $item['index'] < $minIndex) {
                $minIndex = $item['index'];
            }
        }
        return $minIndex;
    }
    private function renderTableOfContentReport($dataSource)
    {
        dd($dataSource);
    }
    private function renderTableOfContentProps($dataSource)
    {
        $minIndex = $this->getMinIndex($dataSource);
        $html = '';
        foreach ($dataSource as $key => $item) {
            $href = '#' . Str::slug($item['value']);
            if ($item['index'] == $minIndex) {
                $value = $item['value'];
                $htmlA = "<a href='$href' class='text-blue-500'>$value</a>";
            } else {
                $value = $item['value'];
                $class = 'ml-' . ($item['index'] - $minIndex) * 4;
                $htmlA = "<a href='$href' class='$class text-blue-500'>$value</a>";
            }
            $html .= "<div class='pr-1 py-1 text-sm'>
                        $htmlA
                      </div>";
        }

        return "<div class='overflow-y-auto'>
                    <div class='block ml-2'>
                        $html
                    </div>
                </div>";
    }
    private function renderTableOfContentCheckList($groupLines, $groupColumn, $groupNames)
    {
        $html = '';
        foreach ($groupLines as $key => $value) {
            $value = $groupNames[$key] ?? "Untitled Group";
            $href = '#' . Str::slug($value);
            // $href = '#' . $groupColumn . '_' . $key . '_' . Str::slug($value);
            $html .= "<div class='pr-1 py-1 text-sm'><a href='$href' class='text-blue-500'>$value</a></div>";
        }
        return "<div class='overflow-y-auto'>
                    <div class='block ml-2'>
                        $html
                    </div>
                </div>";
    }
}
