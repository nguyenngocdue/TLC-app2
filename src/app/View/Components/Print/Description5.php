<?php

namespace App\View\Components\Print;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Constant;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\DateTimeConcern;
use Illuminate\View\Component;

class Description5 extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        private $prop,
        private $dataSource,
        private $modelPath,
        private $type,
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
        $dataSource = $this->dataSource;
        $prop = $this->prop;
        $columnName = $prop['column_name'];
        $content = $dataSource[$columnName];
        $id = $dataSource['id'] ?? CurrentRoute::getEntityId($this->type);
        $label = $prop['label'] ?? '';
        $control = $prop['control'] ?? '';
        switch ($control) {
            case 'status':
                $libStatus = LibStatuses::getFor($this->type);
                $isContent = $dataSource[$columnName];
                $title = (isset($libStatus[$isContent])) ? $libStatus[$isContent]['title'] : $dataSource[$columnName] . " (orphan_status)";
                $content = $isContent ? $title : '';
                break;
            case 'picker_time':
                $content ? $content = date(Constant::FORMAT_TIME, strtotime($content)) : $content;
                break;
            case 'picker_date':
                $content ? $content = date(Constant::FORMAT_DATE_ASIAN, strtotime($content)) : $content;
                break;
            case 'picker_month':
                $content ? $content = date(Constant::FORMAT_MONTH, strtotime($content)) : $content;
                break;
            case 'picker_week':
                $formatFrom = Constant::FORMAT_DATE_MYSQL;
                $formatTo = Constant::FORMAT_WEEK;
                $content ? $content = DateTimeConcern::formatWeekForLoading($content, $formatFrom, $formatTo) : $content;
                break;
            case 'picker_quarter':
                $formatFrom = Constant::FORMAT_DATE_MYSQL;
                $formatTo = Constant::FORMAT_QUARTER;
                $content ? $content = DateTimeConcern::formatQuarterForLoading($content, $formatFrom, $formatTo) : $content;
                break;
            case 'picker_year':
                $content ? $content = date(Constant::FORMAT_YEAR, strtotime($content)) : $content;
                break;
            case 'picker_datetime':
                $content ? $content = date(Constant::FORMAT_DATETIME_ASIAN, strtotime($content)) : $content;
                break;

            default:
                # code...
                break;
        }
        $colSpan = $prop['col_span'];
        $newLine = $prop['new_line'];
        $hiddenLabel = $prop['hidden_label'];
        $relationships = $prop['relationships'];
        return view('components.print.description5', [
            'label' => $label,
            'colSpan' => $colSpan,
            'content' => $content,
            'control' => $control,
            'columnName' => $columnName,
            'id' => $id,
            'type' => $this->type,
            'newLine' => $newLine,
            'hiddenLabel' => $hiddenLabel,
            'modelPath' => $this->modelPath,
            'relationships' => $relationships
        ]);
    }
}
