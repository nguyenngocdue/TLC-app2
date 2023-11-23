<?php

namespace App\Http\Controllers\Entities\ZZTraitApi;

use App\Http\Controllers\Entities\ZZTraitEntity\TraitEntityCRUDStoreUpdate2;
use App\Http\Services\UploadService2;
use App\Models\Kanban_task_bucket;
use App\Models\Kanban_task_cluster;
use App\Models\Kanban_task_group;
use App\Utils\System\Api\ResponseObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait TraitKanbanUpdate
{
    use TraitKanbanItemRenderer;
    use TraitEntityCRUDStoreUpdate2;

    function getOrderedDataSource($id, $item)
    {
        switch ($this->type) {
            case "kanban_task_group":
                return Kanban_task_group::query()
                    ->with(["getTasks" => function ($query) {
                        $query->orderBy('order_no');
                    }])
                    ->find($id);
            case "kanban_task_cluster":
                return Kanban_task_cluster::query()
                    // ->with("getGroups.getTasks")
                    ->with(["getGroups" => function ($query) {
                        $query->orderBy('order_no');

                        $query->with(["getTasks" => function ($query) {
                            $query->orderBy('order_no');
                        }]);
                    }])
                    ->find($id);
            case "kanban_task_bucket":
                return Kanban_task_bucket::query()
                    ->with(["getPages" => function ($query) {
                        $query->orderBy('order_no');
                    }])
                    ->find($id);
            case "kanban_task":
            case "kanban_task_page":
                return $item;
            default:
                throw new \Exception("Unknown how to get ordered dataSource of " . $this->type);
        }
    }

    function handleAttachments(Request $request, $attachmentProps, $id)
    {
        $uploadService2 = new UploadService2($this->modelPath);
        $uploadService2->store($request, $this->modelPath, $id);
        $input = $request->input();
        foreach ($attachmentProps as $attachmentProp) {
            $ids = trim($input[substr($attachmentProp, 1)]['toBeDeleted']);
            if ($ids) {
                $toBeDeletedIds = explode(",", $ids);
                $uploadService2->destroy($toBeDeletedIds);
            }
        }
    }

    function updateItemRenderProps(Request $request)
    {
        $input = $request->input();
        ['id' => $id, 'groupWidth' => $groupWidth] = $input;
        $props = $this->getProps1();

        $item = $this->modelPath::find($id);
        $table = $this->modelPath::getTableName();
        $item->update($input);
        $this->handleCheckboxAndDropdownMulti($request, $item, $props['oracy_prop']);
        $this->handleAttachments($request, $props['attachment'], $id);
        // Log::info($request->input());

        $orderedItem = $this->getOrderedDataSource($id, $item);

        $table = ($table == 'kanban_task_pages') ? 'kanban_task_tocs' : $table;
        $renderer = $this->renderKanbanItem($table, $orderedItem, $groupWidth);

        return ResponseObject::responseSuccess(['renderer' => $renderer], ['table' => $table], "Updated");
    }
}
