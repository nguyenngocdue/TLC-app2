<?php

namespace App\View\Components\Controls;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Uploadfiles extends Component
{
    public function __construct(private $id, private $colName, private $action, private $tablePath)
    {
    }

    public function render()
    {
        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $id = $this->id;
        $colName = $this->colName;
        $action = $this->action;

        $cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
        $cateIdName = [];
        foreach ($cateAttachment as $key => $value) {
            $cateIdName[$value->id] = $value->name;
        }
        if (!isset(array_flip($cateIdName)[$colName])) {
            $error = "Not found $colName in media_categories";
            return view('components.render.alert')->with(compact('error'));
        }
        if ($action === 'create') {
            $infMedia = [];
            $cateIdName = [];
            $url_media = "";
            return view('components.controls.uploadfiles')->with(compact('action', 'infMedia', 'cateIdName', 'colName', 'path'));
        }
        $newItemModel = $this->tablePath::find($id);
        $mediaOnModel = $newItemModel->media;

        $infMedia = [];
        foreach ($mediaOnModel as $mediaOnModel) {
            array_push($infMedia, $mediaOnModel->getAttributes());
        }

        $attachHasMedia = [];
        foreach ($infMedia as $media) {
            $cat = $media['category'];
            $attachHasMedia[$cateIdName[$cat]][] = $media;
        }

        return view('components.controls.uploadfiles')->with(compact('action', 'attachHasMedia', 'cateIdName', 'colName', 'path'));
    }
}
