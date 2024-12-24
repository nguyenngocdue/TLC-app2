<?php

namespace App\View\Components\Dashboards;

use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class PreSignOffRequest extends Component
{
    function getDataSource()
    {
        $uid = CurrentUser::id();

        $allSheets = Qaqc_insp_chklst_sht::query()
            ->where('status', 'first_sign_off')
            ->where('assignee_3', $uid)
            ->with([
                'getChklst' => function ($query) {
                    $query->with(['getProdOrder' => function ($query) {
                        // $query->with('getSubProject');;
                    }]);
                },
                'getAssignee3' => function ($query) {},
                'getLines' => function ($query) {
                    $query->where('control_type_id', 7);
                },
            ])
            ->get();

        foreach ($allSheets as &$sheet) {
            $href = route('qaqc_insp_chklst_shts.edit', $sheet->id);
            $sheet->id_link = "<a class='text-blue-700' href='$href'>{$sheet->name}</a>";
            $sheet->assignee_name = $sheet->getAssignee3->name;
            $sheet->module_name = $sheet->getChklst->getProdOrder->compliance_name;
            // $sheet->sub_project_name = $sheet->getChklst->getProdOrder->getSubProject->name;
            $sheet->signature = $sheet->getLines->map(function ($line) {
                if ($line->value == null) return "";
                $svg = $line->value;
                // $svg = preg_replace('/\s*(width|height)="[^"]*"/', '', $svg);
                $signed = $svg ? 'Signed: ' : '';
                return "<div>"
                    . $signed
                    . $line->getInspector->name
                    . "</div>";
            })->join(' ');
        }

        // dump($allSheets);

        return $allSheets;
    }

    function getColumns()
    {
        return [
            // ['dataIndex' => 'sub_project_name'],
            ['dataIndex' => 'module_name'],
            ['dataIndex' => 'id_link', 'title' => "Sheet Name",],
            ['dataIndex' => 'status', 'title' => "Sheet Status", 'renderer' => 'status', 'align' => 'center'],
            ['dataIndex' => 'assignee_name'],
            ['dataIndex' => 'signature', 'title' => "All Signatures"],
        ];
    }

    function render()
    {
        $view = view('components.dashboards.my-view', [
            'title' => "Pre-Sign-Off Request",
            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSource(),
            'icon' => "fa-duotone fa-signature",
        ]);

        return "<div class='w-1/2'>$view</div>";
    }
}
