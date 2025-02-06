<?php

namespace App\View\Components\Renderer\ViewAllTreeType;

use App\Models\Department;
use App\Models\Pp_doc;
use App\Models\Pp_folder;
use Illuminate\Support\Facades\Log;

class PpProcedurePolicy extends ViewAllTypeTreeExplorer
{
    protected $showSearch = true;

    protected function getApiRoute()
    {
        return route("pp_procedure_policy_tree_explorer");
    }

    private $mapping = [
        'App\\Models\\Pp_folder' => 'pp_folder',
        'App\\Models\\Department' => 'department',
    ];

    private function getShortName($modelPath)
    {
        // if (!isset($this->mapping[$modelPath])) {
        //     dd("Model path not found in mapping: [" . $modelPath . "]");
        // }
        return $this->mapping[$modelPath];
    }

    protected function getFolders()
    {
        $folders = Pp_folder::query()
            // ->where('parent_id', null)
            ->orderBy('name')
            ->get();

        $items = [];
        $type = $this->getShortName('App\\Models\\Pp_folder');

        foreach ($folders as $folder) {
            $item['id'] = $type . "|||" . $folder->id;
            $item['text'] = $folder->name;
            if ($folder->parent_id) {
                $item['parent'] = $this->getShortName($folder->parent_type) . "|||" . $folder->parent_id;
            } else {
                $item['parent'] = '#';
            }
            $item['state'] = ['opened' => $folder->opened];
            $item['data'] = [
                'draggable' => $folder->draggable,
                'droppable' => $folder->droppable,
                "parent_id" => $folder->id,
                "parent_type" => "App\\Models\\Pp_folder",
                "my_type" => "pp_folder",
                "my_id" => $folder->id,
            ];
            $items[] = $item;
        }

        $departments = Department::query()
            ->where('hide_in_pp', 0)
            ->with(['getHOD' => fn($q) => $q->with(["getAvatar"])])
            ->orderBy('name')
            ->get();

        $type = $this->getShortName('App\\Models\\Department');
        foreach ($departments as $department) {
            $url = $department->getHOD->getAvatar?->url_thumbnail ?? false;
            $avatar =  $url ? app()->pathMinio() . $url : '/images/avatar.jpg';
            $src = "<img class='rounded-full ml-2 mr-2' heigh=24 width=24 src='" . $avatar . "' />";
            $item['id'] = $type . "|||" . $department->id;
            // $item['text'] = $department->name;
            $item['text'] = "<span class='flex' nameForSort='" . $department->name . "'>" . $src . $department->name . "</span>";
            $item['parent'] = 'pp_folder|||1300';
            $item['data'] = [
                "parent_id" => $department->id,
                "parent_type" => "App\\Models\\Department",
                "draggable" => false,
                "my_type" => "department",
            ];
            $item['icon'] = false;
            $items[] = $item;
        }

        // dump($items);

        return $items;
    }

    protected function getDocs()
    {
        $docs = Pp_doc::query()
            ->orderBy('name')
            ->get();

        $items = [];
        foreach ($docs as $doc) {
            $parent_type = $this->getShortName($doc->parent_type);
            $item['id'] = $doc->id;
            $item['text'] = $doc->name;
            $item['parent'] = $parent_type . '|||' . $doc->parent_id;
            $item['icon'] = "fa-regular fa-file text-blue-400";
            $item['data'] = [
                "my_type" => "pp_doc",
                "my_id" => $doc->id,
            ];
            $items[] = $item;
        }

        // dump($docs);

        return $items;
    }

    protected function getTree()
    {
        return
            [
                ...$this->getFolders(),
                ...$this->getDocs(),
            ];
    }
}
