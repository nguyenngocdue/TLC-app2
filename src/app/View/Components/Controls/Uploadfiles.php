<?php

namespace App\View\Components\Controls;

use App\Models\Media;
use App\Utils\Constant;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Uploadfiles extends Component
{
    public function __construct(private $id, private $colName, private $action, private $tablePath, private $labelName)
    {
    }

    public function render()
    {

        $dataMedia = json_decode(DB::table('media')->where([['owner_id', '=', 1], ['object_id', '=', null], ['object_type', '=', null]])->select('id', 'category')->get(), true);
        $media_cateTb = json_decode(DB::table('media_categories')->select('id', 'name')->get(), true);
        $ids_names_cateMedia = array_combine((array_column($media_cateTb, 'id')), (array_column($media_cateTb, 'name')));
        $ids_names_cateTb = array_combine((array_column($dataMedia, 'id')), (array_column($dataMedia, 'category')));
        $idsHasAttachMent = array_values(array_unique($ids_names_cateTb));
        $attachHasMedia = [];
        foreach ($ids_names_cateTb as $key => $value) {
            foreach ($idsHasAttachMent as $cate) {
                if ($value === $cate) {
                    $ele = (array) DB::table('media')->find($key);
                    $attachHasMedia[$ids_names_cateMedia[$cate]][] = $ele;
                    break;
                }
            }
        }

        $path = env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
        $colName = $this->colName;
        $action = $this->action;
        $labelName = $this->labelName;
        return view('components.controls.uploadfiles')->with(compact('action', 'attachHasMedia', 'colName', 'path', 'labelName'));
    }
}
