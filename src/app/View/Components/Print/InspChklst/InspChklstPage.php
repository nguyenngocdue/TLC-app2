<?php

namespace App\View\Components\Print\InspChklst;

use App\Http\Services\LoadManyCheckpointService;
use App\Models\Hse_insp_chklst_line;
use App\Models\Hse_insp_chklst_sht;
use App\Models\Hse_insp_tmpl_line;
use App\Models\Hse_insp_tmpl_sht;
use App\Models\Qaqc_insp_chklst_line;
use App\Models\Qaqc_insp_chklst_sht;
use App\Models\Qaqc_insp_tmpl_line;
use App\Models\Qaqc_insp_tmpl_sht;
use App\Utils\Support\CurrentRoute;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class InspChklstPage extends Component
{
    private $modelPath;

    public function __construct(
        private $sheet,
    ) {
        $this->modelPath = Str::modelPathFrom($sheet->getTable());
    }

    private function getProgressData($checkPoints)
    {
        $total = 0;
        foreach ($checkPoints as $checkpoint) {
            if (!in_array($checkpoint->getControlType->slug, ['radio'])) continue;
            $total++;
            $key = $checkpoint->getControlValue?->behavior_of;
            if (!isset($count[$key])) {
                $count[$key] = [
                    'id' => $key,
                    'color' => $checkpoint->getControlValue?->getColor?->name ?? "gray",
                    'value' => 0,
                ];
            }

            $count[$key]['value']++;
        }

        foreach ($count as $key => $item) {
            $count[$key]['percent'] = ($total > 0) ? round(100 * $item['value'] / $total, 2) . '%' : "0%";
            if ($count[$key]['id']) {
                $count[$key]['label'] = config("insp_chklst." . $key) . "<br/>" . $item['value'] . "/$total";
            }
        }

        $result =  array_values($count);
        usort($result, function ($a, $b) {
            // If $a['id'] is null, push it to the end
            if ($a['id'] === null) return 1;
            // If $b['id'] is null, push it to the end
            if ($b['id'] === null) return -1;

            return $a['id'] <=> $b['id'];
        });

        return $result;
    }

    function getProjectBoxQaqc()
    {
        $projectBox = [
            "Organization" => config("company.name"),
            "Project" => $this->sheet->getChklst?->getSubProject->getProject->description,
            "Sub Project" => $this->sheet->getChklst?->getSubProject->name,
            "Production Name" => $this->sheet->getChklst?->getProdOrder->compliance_name,
        ];
        return $projectBox;
    }

    function getProjectBoxHse($sheet)
    {
        $projectBox = [
            "Location" => $sheet->getWorkplace->name ?? "Unknown Location",
            "Start Date" => $sheet->start_date ?? "Unknown Date",
            "Inspector" => $sheet->getOwner->name ?? "Unknown Inspector",
            "Person Incharge" => $sheet->getAssignee1->name ?? "Unknown Person Incharge",
        ];
        return $projectBox;
    }

    function render()
    {
        $entity = $this->modelPath::query();
        // if ($trashed) $entity = $entity->withTrashed();
        $entity = $entity->where('id', $this->sheet->id);

        $lineModelPath = "";
        $signOffSignatureFn = "";
        $signOffUserFn = "";
        $projectBox = [];
        switch ($this->modelPath) {
            case Hse_insp_chklst_sht::class:
                $lineModelPath = Hse_insp_chklst_line::class;
                $projectBox = $this->getProjectBoxHse($this->sheet);
                break;
            case Hse_insp_tmpl_sht::class:
                $lineModelPath = Hse_insp_tmpl_line::class;
                break;
            case Qaqc_insp_chklst_sht::class:
                $lineModelPath = Qaqc_insp_chklst_line::class;
                $projectBox = $this->getProjectBoxQaqc();

                $signOffSignatureFn = "signature_qaqc_chklst_3rd_party";
                $signOffUserFn = "signature_qaqc_chklst_3rd_party_list";

                $entity = $entity->with([
                    $signOffSignatureFn => function ($q) {
                        $q->with(['getUser' => function ($q) {
                            $q->with(['getAvatar']);
                        }]);
                    },
                    $signOffUserFn,
                    'council_member_list',
                    // 'council_member',
                    // 'getChklst.getSubProject.getProject',
                ]);
                break;
            case Qaqc_insp_tmpl_sht::class:
                $lineModelPath = Qaqc_insp_tmpl_line::class;
                break;
            default:
                dd("Error: Model Path not found - " . $this->modelPath);
                break;
        }

        $entity = $entity->get()->first();

        // $this->checkIsExternalInspectorAndNominated($entity);
        // $this->checkIsCouncilMemberAndNominated($entity);

        $service = new LoadManyCheckpointService();
        $entityLines = $service->getCheckpointDataSource($entity->getLines->sortBy('order_no'), $lineModelPath);
        ['groupedCheckpoints' => $groupedCheckpoints, 'checkPoints' => $checkPoints] = $entityLines;

        $nominatedUserIds = $entity->{$signOffUserFn}?->pluck('id')->toArray();
        // dump($nominatedUserIds);
        $signOff = $entity->{$signOffSignatureFn}?->filter(function ($item) use ($nominatedUserIds) {
            return in_array($item->user_id, $nominatedUserIds);
        });
        // dump($signOff);

        $progressData = $this->getProgressData($checkPoints);

        $type = $this->sheet->getTable();

        return view("components.print.insp-chklst.insp-chklst-page", [
            "type" => $type,
            "topTitle" => CurrentRoute::getTitleOf($type),
            "projectBox" => $projectBox,
            "sheet" => $this->sheet,
            "progressData" => $progressData,
            "groupedCheckpoints" => $groupedCheckpoints,
            "signOff" => $signOff,
        ]);
    }
}
