<?php

namespace App\View\Components\Renderer\ConqaArchive;

use App\Http\Controllers\Api\v1\qaqc\ConqaArchiveRendererController;
use Illuminate\View\Component;
use Illuminate\Http\Request;

class ConqaArchive extends Component
{
    private $folderName;
    private $type;
    private $projName;
    private $folderUuid;

    public function __construct(
        private $item,
        private $loadFromStorage = false,
    ) {
        $this->projName = $item->name;
        $this->folderUuid = $item->uuid;
        $this->type = $item->type ?? "folder";
    }

    public function loadTree(string $path, string $folderUuid, string $parent): array
    {
        $jsonFileName = $path . $folderUuid . ".json";
        $json = json_decode(file_get_contents($jsonFileName));
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

    function renderFolder()
    {
        if ($this->loadFromStorage) {
            $path = storage_path("app/conqa_archive/database/{$this->projName}/");
        } else {
            $path = env('AWS_ENDPOINT') . '/conqa-backup/database/' . $this->projName . "/";
        }
        $tree = $this->loadTree($path, $this->folderUuid, "#");
        return view(
            "components.renderer.conqa_archive.conqa_archive",
            [
                "tree" =>  $tree,
                "route" => route("render_conqa_archive_checklist"),
                "projName" => $this->projName,
                "folderName" => $this->folderName,
            ]
        );
    }

    function renderChecklist()
    {
        $request = new Request();
        $request->merge([
            'folderUuid' => $this->folderUuid,
            'projName' => $this->projName,
        ]);
        $x = (new ConqaArchiveRendererController())->render($request);
        return $x;
    }

    function render()
    {
        if ($this->type == "folder") return $this->renderFolder();
        if ($this->type == 'checklist') return $this->renderChecklist();
    }
}
