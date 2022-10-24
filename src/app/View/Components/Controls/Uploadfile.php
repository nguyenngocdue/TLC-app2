<?php

namespace App\View\Components\Controls;

use App\Models\Media;
use App\Models\User;
use Illuminate\View\Component;

class Uploadfile extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    private $id;
    private $colName;
    private $idAvatar;
    public function __construct($id, $colName, $idAvatar)
    {
        $this->id = $id;
        $this->colName = $colName;
        $this->idAvatar = $idAvatar;
    }

    public function render()
    {
        $path = env('AWS_ENDPOINT', 'http://127.0.0.1:9000') . '/' . env('AWS_BUCKET', 'hello-001') . '/';
        $id = $this->id;
        $colName = $this->colName;
        $idAvatar = $this->idAvatar;
        $user = User::where('id', $id)->first();

        $media = Media::where('id', $idAvatar)->first();
        if (is_null($media)) return view('components.controls.noItem');
        $fileName = $media['filename'];
        $url_thumbnail = $media['url_thumbnail'];
        $url_media = $media['url_media'];
        return view('components.controls.uploadfile')->with(compact('id', 'colName', 'fileName', 'url_thumbnail', 'url_media', 'path'));
    }
}
