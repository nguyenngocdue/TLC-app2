<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Uploadfiles extends Component
{
    public function __construct(private $id, private $colName, private $action, private $tablePath, private $labelName)
    {
    }

    public function render()
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $colName = $this->colName;
        $action = $this->action;
        $labelName = $this->labelName;
        $id = $this->id;
        $owner_id =  (int)Auth::user()->id;

        $media_cateTb = json_decode(DB::table('attachment_categories')->select('id', 'name')->get(), true);
        $ids_names_media_cateTb = array_combine((array_column($media_cateTb, 'id')), (array_column($media_cateTb, 'name')));

        $mediaDB = json_decode(DB::table('attachments')->where([['owner_id', '=',  $owner_id], ['object_id', '!=', null], ['object_type', '!=', null]])->select('id', 'category', 'object_id')->get(), true);
        $orphanMediaDB = json_decode(DB::table('attachments')->where([['owner_id', '=',  $owner_id], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category', 'object_id')->get(), true);

        $_mediaDB = $action === 'create' ? [] : $mediaDB;

        $mergeMediaDB  = array_merge($_mediaDB, $orphanMediaDB);

        $attachHasMedia = [];
        foreach ($mergeMediaDB as $key => $value) {
            if ($colName ===  $ids_names_media_cateTb[$value['category']]) {
                if (!is_null($value['object_id']) && $id != "") {
                    if ($id * 1 === $value['object_id']) {
                        $ele = (array) DB::table('attachments')->find($value['id']);
                        $attachHasMedia[$colName][] = $ele;
                    }
                }
            }
            if ($action === 'create' && $colName ===  $ids_names_media_cateTb[$value['category']]) {
                $ele = (array) DB::table('attachments')->find($value['id']);
                $attachHasMedia[$colName][] = $ele;
            }
        }
        return view('components.controls.uploadfiles')->with(compact('action', 'attachHasMedia', 'colName', 'path', 'labelName'));
    }
}
