<?php

namespace App\View\Components\Renderer\ConqaArchive;

use Illuminate\View\Component;

class ConqaArchive extends Component
{
    private $folderName;
    private $projName;
    private $folderUuid;

    public function __construct(
        private $item,
        // private $projName = "BTH",
        // private $folderUuid = "92d6cac3-84de-4c52-b37c-2825c773cba5",
    ) {
        // dump($item);
        $this->projName = $item->name;
        $this->folderUuid = $item->uuid;
    }

    private function loadTree(string $path, string $folderUuid, string $parent): array
    {
        $json = json_decode(file_get_contents($path . $folderUuid . ".json"));
        // dump($json);
        if ($parent == '#') {
            $this->folderName = $json->tree->{$folderUuid}->name;
        }
        $children = $json->tree->{$folderUuid}->children;
        // dump($children);
        $tree = [];
        foreach ($children as $child) {
            $tree[] =  $json->tree->{$child};
        }
        usort($tree, fn ($a, $b) => ($a->name) <=> ($b->name));
        // usort($tree, fn ($a, $b) => ($a->order ?? 9999) <=> ($b->order ?? 9999));
        foreach ($tree as &$child) {
            $child->parent = $parent;
            $child->text = $child->name;
            $child->icon = ($child->type == 'folder') ?: (($child->type == 'checklist') ? "fa fa-ballot-check text-blue-400" : "fa fa-ballot-check text-red-400");

            unset($child->name);
        }

        $grands = [];
        foreach ($tree as $child1) {
            if ($child1->type == 'folder') {
                $grands[] = $this->loadTree($path, $child1->id, $child1->id);
            }
        }

        foreach ($grands as $grand) {
            $tree = [...$tree, ...$grand];
        }

        return $tree;
    }

    function render()
    {
        // $path = storage_path("app/conqa_archive/database/{$this->projName}/");
        $path = env('AWS_ENDPOINT') . '/conqa-backup/database/' . $this->projName . "/";
        $tree = $this->loadTree($path, $this->folderUuid, "#");
        return view(
            "components.renderer.conqa_archive.conqa_archive",
            [
                "tree" =>  $tree,
                "route" => route("render_checklist"),
                "projName" => $this->projName,
                "folderName" => $this->folderName,
            ]
        );
    }
}
