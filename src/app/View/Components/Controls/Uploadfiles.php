<?php

namespace App\View\Components\Controls;

use App\Models\Media;
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
        $id = $this->id;
        $colName = $this->colName;
        $action = $this->action;
        $labelName = $this->labelName;

        $cateAttachment = DB::table('media_categories')->select('id', 'name')->get();
        $cateIdName = [];
        foreach ($cateAttachment as $key => $value) {
            $cateIdName[$value->id] = $value->name;
        }
        if (!isset(array_flip($cateIdName)[$colName])) {
            $message = "Not found $colName in media_categories";
            $type = "warning";
            return view('components.feedback.alert')->with(compact('message', 'type'));
        }
        if ($action === 'create') {
            $colNameMediaUploaded = session('colNameMediaUploaded') ?? [];
            // dd($colNameMediaUploaded);
            $attachHasMedia = [];
            foreach ($colNameMediaUploaded as $key => $attach) {
                if (!is_null(Media::find($key * 1))) {
                    $attachHasMedia[$attach][] = Media::find($key * 1)->getAttributes();
                }
            }
            return view('components.controls.uploadfiles')->with(compact('action', 'attachHasMedia', 'colName', 'path', "labelName"));
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



        $colNameMediaUploaded = session('colNameMediaUploaded') ?? [];
        $attachFaildUpload = [];
        foreach ($colNameMediaUploaded as $key => $attach) {
            if (!is_null(Media::find($key * 1))) {
                $attachFaildUpload[$attach][] = Media::find($key * 1)->getAttributes();
            }
        }
        // session(['colNameMediaUploaded' => []]);


        foreach ($attachFaildUpload as $key => $media) {
            // dd($attachHasMedia, $attachFaildUpload, $key);
            if (isset($attachHasMedia[$key])) {
                array_push($attachHasMedia[$key], ...$media);
            } else {
                $attachHasMedia += $attachFaildUpload;
                break;
            }
        }

        // dd($attachHasMedia, $attachFaildUpload);

        return view('components.controls.uploadfiles')->with(compact('action', 'attachHasMedia', 'colName', 'path', 'labelName'));
    }
}
