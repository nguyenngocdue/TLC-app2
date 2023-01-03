<?php

namespace App\View\Components\Controls;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\Support\Str;


class Uploadfiles extends Component
{
    public function __construct(
        private $id,
        private $colName,
        private $action,
        private $label,
        private $type,
    ) {
    }

    public function render()
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $name = $this->colName;
        $action = $this->action;
        $label = $this->label;
        $id = $this->id;
        $owner_id =  (int)Auth::user()->id;

        $idNameCatesDB = Helper::getDataDbByName('fields', 'id', 'name');

        $mediaDB = json_decode(DB::table('attachments')->where([['owner_id', '=',  $owner_id], ['object_id', '!=', null], ['object_type', '!=', null]])->select('id', 'category', 'object_id')->get(), true);
        $orphanAttachmentDB = json_decode(DB::table('attachments')->where([['owner_id', '=',  $owner_id], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category', 'object_id')->get(), true);

        $_mediaDB = $action === 'create' ? [] : $mediaDB;
        $mergeMediaDB  = array_merge($_mediaDB, $orphanAttachmentDB);

        $attachmentData = [];
        foreach ($mergeMediaDB as $attach) {
            $ele = (array)DB::table('attachments')->find($attach['id']);
            $checkName = $idNameCatesDB[$attach['category']] === $name;
            if ($id) {
                if ($checkName && $id * 1 === $attach['object_id']) {
                    $attachmentData[$name][] = $ele;
                }
            } else {
                if ($checkName) {
                    $attachmentData[$name][] = $ele;
                }
            }
        }

        $showToBeDeleted = env('APP_ENV') === 'local';
        return view('components.controls.uploadfiles')->with(compact('action', 'attachmentData', 'name', 'label', 'showToBeDeleted', 'path'));
    }
}
