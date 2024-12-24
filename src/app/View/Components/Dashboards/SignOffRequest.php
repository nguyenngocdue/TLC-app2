<?php

namespace App\View\Components\Dashboards;

use App\Models\Qaqc_insp_chklst_sht;
use App\Utils\Support\CurrentUser;
use Illuminate\View\Component;

class SignOffRequest extends Component
{
    function getDataSource()
    {
        $uid = CurrentUser::id();

        $allSheets = Qaqc_insp_chklst_sht::query()
            ->where('status', 'pending_audit')
            // ->where('assignee_3', $uid)
            ->with([
                'getChklst' => function ($query) {
                    $query->with(['getProdOrder' => function ($query) {
                        // $query->with('getSubProject');;
                    }]);
                },
                // 'getAssignee3' => function ($query) {},
                'getLines' => function ($query) {
                    $query->where('control_type_id', 7);
                },
                'signature_qaqc_chklst_3rd_party' => function ($query) use ($uid) {
                    $query->where('user_id', $uid);
                },
            ])
            ->whereHas('signature_qaqc_chklst_3rd_party', function ($query) use ($uid) {
                $query->where('user_id', $uid);
            })
            // ->whereHas('getChklst', function ($query) {
            //     $query->whereHas('getProdOrder', function ($query) {
            //         $query->where('sub_project_id', 145);
            //     });
            // })
            ->get();

        foreach ($allSheets as &$sheet) {
            $href = route('qaqc_insp_chklst_shts.edit', $sheet->id);
            $sheet->id_link = "<a class='text-blue-700' href='$href'>{$sheet->name}</a>";
            $sheet->assignee_name = $sheet->signature_qaqc_chklst_3rd_party
                ->map(fn($signature) => $signature->getUser->name)
                ->join(', ');

            $sheet->module_name = $sheet->getChklst->getProdOrder->compliance_name;
            $sheet->signature = $sheet->signature_qaqc_chklst_3rd_party
                ->map(function ($signature) {
                    $svg = $signature->value;
                    return $svg ? 'Signed: ' . $signature->getUser->name : '';
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
            'title' => "Sign-Off Request",
            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSource(),
            'icon' => "fa-duotone fa-signature",
        ]);

        return "<div class='w-1/2'>$view</div>";
    }
}
