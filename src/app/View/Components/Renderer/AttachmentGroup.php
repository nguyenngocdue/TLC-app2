<?php

namespace App\View\Components\Renderer;

use App\Models\Term;
use Illuminate\View\Component;

class AttachmentGroup extends Component
{
    private $attachments = [];
    private $debugAttachment = false;

    public function __construct(
        private $name,
        private $value = "", //either a string of serialized array of attachments object or array or attachments 
        private $readOnly = false,
        private $destroyable = true,
        private $showUploadFile = true,
        private $label = '',
        private $properties = [],
        private $openType = 'gallery',
        private $gridCols = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 lg:gap-3 md:gap-2 sm:gap-1',
        private $groups = [null => ""],
    ) {
        //
        if (is_array($value)) {
            $this->attachments = $value;
        } else {
            $this->attachments = (json_decode(htmlspecialchars_decode($value))) ?? [];
            //Convert to array if object given
            foreach ($this->attachments as &$attachment) if (is_object($attachment)) $attachment = (array)$attachment;
        }
        $groupArray = json_decode($this->properties['group_array'] ?? null, true);
        if ($groupArray) {
            $this->groups = $groupArray;
            // dump($groupArray);
            // dump("Override by properties");
        }
        // dump($this->groups);
    }

    private function GroupBySubCat()
    {
        $result = [];
        if ($this->groups) {
            foreach ($this->groups as $groupId => $groupName) {
                $result[$groupId] = [
                    "name" => $groupName,
                    "items" => [],
                ];
            }
        } else {
            //This is for ICS, sheet that doesn't have group and have no existing attachment
            $result[null] = [
                "name" => "",
                "items" => [],
            ];
        }
        foreach ($this->attachments as $attachment) {
            $subCatId = $attachment['sub_category'];
            if (!isset($result[$subCatId])) $result[$subCatId] = [];
            $result[$subCatId]["items"][] = $attachment;
        }
        foreach (array_keys($result) as $key) {
            $subCatId = $key;
            $subCat = Term::findFromCache($subCatId);
            $result[$key]["name"] = $subCat?->name; // ?: "No Name";
        }
        return $result;
    }

    public function render()
    {
        $this->attachments = $this->GroupBySubCat();
        // dump($this->attachments);
        $colCount = count($this->attachments);
        $span = "w-full";
        switch ($colCount) {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
                $span = "w-1/$colCount";
                break;
        }
        return view('components.renderer.attachment-group', [
            'hiddenOrText' => $this->debugAttachment ? "text" : "hidden",
            'attachmentGroups' => $this->attachments,
            'width' => $span,

            'name' => $this->name,
            'readOnly' => $this->readOnly,
            'destroyable' => $this->destroyable,
            'showUploadFile' => $this->showUploadFile,
            'label' => $this->label,
            'properties' => $this->properties,
            'openType' => $this->openType,
            'gridCols' => $this->gridCols,
        ]);
    }
}
