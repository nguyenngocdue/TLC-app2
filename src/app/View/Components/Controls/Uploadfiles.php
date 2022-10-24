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
        if ($action === 'create') {
            $fileName = "";
            $url_thumbnail = "";
            $url_media = "";
            $path = "";
            return view('components.controls.uploadfiles')->with(compact('id', 'colName', 'fileName', 'url_thumbnail', 'url_media', 'path', 'action'));
        }


        // get table's name in database
        $insTable = new $this->tablePath;
        $tableName = $insTable->getTable();
        $currentTable = DB::table($tableName)->where('id', $id)->first();

        $media = Media::where('id', $currentTable->$colName)->first();
        $fileName = $media['filename'];
        $url_thumbnail = $media['url_thumbnail'];
        $url_media = $media['url_media'];
        return view('components.controls.uploadfiles')->with(compact('id', 'colName', 'fileName', 'url_thumbnail', 'url_media', 'path', 'action'));
    }
}
