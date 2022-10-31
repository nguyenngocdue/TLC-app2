<?php

namespace App\View\Components\Controls;

use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Uploadfiles extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id;
    private $colName;
    private $tablePath;

    public function __construct($id, $colName, $action, $tablePath)
    {

        $this->id = $id;
        $this->colName = $colName;
        $this->action = $action;
        $this->tablePath = $tablePath;
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



        return view('components.controls.uploadfiles')->with(compact('action', 'infMedia', 'cateIdName', 'colName', 'path'));
    }
}
