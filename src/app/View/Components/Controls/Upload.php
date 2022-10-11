<?php

namespace App\View\Components\Controls;

use App\Models\Media;
use App\Models\User;
use Illuminate\View\Component;

class Upload extends Component
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
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

        // dd($id, $idAvatar);
        return view('components.controls.upload')->with(compact('id', 'colName', 'fileName', 'url_thumbnail', 'url_media', 'path'));
    }
}
