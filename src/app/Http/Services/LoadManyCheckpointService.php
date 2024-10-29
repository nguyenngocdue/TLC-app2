<?php

namespace App\Http\Services;

use App\Models\Hse_insp_chklst_line;
use App\Models\Prod_order;
use App\Models\Qaqc_insp_chklst_line;

class LoadManyCheckpointService
{
    public static function getAttachmentGroups($sheet)
    {
        $isAttachmentGrouped = $sheet->getTmplSheet->is_attachment_grouped;
        $groups = null;
        if ($isAttachmentGrouped) {
            $roomList = $sheet->getChklst->getProdOrder->{Prod_order::class}->getPjType->getRoomList;
            //This line used when eager loading turned off
            // $roomList = $sheet->getChklst->getProdOrder->getMeta->getPjType->getRoomList;
            $groups = $roomList->pluck('name', 'id');
        }
        return $groups;
    }

    public function getCheckpointDataSource($paginatedDataSource, $lineModelPath)
    {
        $dataSource = $paginatedDataSource->sortBy('order_no');
        $checkPointIds = $dataSource->pluck('id');

        $builder = $lineModelPath::query()
            ->whereIn('id', $checkPointIds)
            ->with([
                'getGroup' => fn($q) => $q, // To get the group name
                'getSheet' => function ($q) use ($lineModelPath) {
                    $q->with('getTmplSheet');
                    if ($lineModelPath == Qaqc_insp_chklst_line::class) {
                        $q->with([
                            'getChklst' => function ($q) {
                                $q->with(['getProdOrder' => function ($q) {
                                    $q->with([
                                        'getMeta' => function ($q) {
                                            $q->with(['getPjType' => function ($q) {
                                                $q->with('getRoomList');
                                            }]);
                                        },
                                        // 'getSubProject' => function ($q) {
                                        //     $q->with('getProject');
                                        // },
                                        // 'getProdRouting' => fn($q) => $q,
                                    ]);
                                }]);
                            }
                        ]);
                    }
                },


                'getControlValue' => fn($q) => $q, // Selected Value: Pass/Fail/NA - for showing NCR/CAR Box
                'getControlGroup' => function ($q) {
                    $q->with(['getControlValues' => function ($q) {
                        $q->with(['getColor', 'getBehaviorOf']);
                    }]);
                },
                'getControlType' => fn($q) => $q, // Text or Radio or Signature
                'getInspector' => function ($q) {
                    $q->with(['getAvatar']);
                },

                'insp_photos' => fn($q) => $q,
                'insp_comments' => function ($q) {
                    $q->with(['getOwner' => function ($q) {
                        $q->with([
                            'getAvatar',
                            'getPosition',
                        ]);
                    }]);
                },
            ]);

        $columnGroupId = "";
        $categoryName = "";
        if ($lineModelPath == Qaqc_insp_chklst_line::class) {
            $builder->with([
                'getNcrs' => function ($q) {
                    $q->with('getOwner');
                },
            ]);
            $columnGroupId = "qaqc_insp_group_id";
            $categoryName = "qaqc_insp_control_value_id";
        }
        if ($lineModelPath == Hse_insp_chklst_line::class) {
            $builder->with(['getCorrectiveActions' => function ($q) {
                $q->with(['getOwner', 'getWorkArea']);
            }]);
            $columnGroupId = "hse_insp_group_id";
            $categoryName = "hse_insp_control_value_id";
        }

        $checkPoints = $builder->orderBy('order_no')->get();
        // dump($checkPoints[0]);
        // dump($checkPoints[0]->getSheet->getChklst->getProdOrder);
        // dump($checkPoints[1]);
        // dump($checkPoints[2]);
        // dd();

        $groupedCheckpoints = $checkPoints
            ->groupBy($columnGroupId)
            ->map(function ($items, $key) {
                return [
                    'groupId' => $key ?: null,
                    'groupName' => $items[0]->getGroup->name,
                    'checkpoints' => $items,
                ];
            })
            ->sortBy('order_no')
            ->values();
        // dump($groupedCheckpoints);

        return [
            'dataSource' => $dataSource,
            'checkPointIds' => $checkPointIds,
            'checkPoints' => $checkPoints,
            'groupedCheckpoints' => $groupedCheckpoints,
            'categoryName' => $categoryName,
        ];
    }
}
