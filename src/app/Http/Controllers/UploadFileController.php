<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LdapRecord\Query\Events\Read;
use Intervention\Image\Facades\Image;

class UploadFileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = Media::all();
        $path = env('AWS_ENDPOINT', 'http://127.0.0.1:9000') . '/' . env('AWS_BUCKET', 'hello-001') . '/';
        //dd(Storage::disk('s3')->temporaryUrl($files[0]->url_thumbnail, now()->addMinutes(5)));
        return view('uploadfiles.index')->with(compact('files', 'path'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = $file->getClientOriginalName();
                $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                $fileNameNormal = pathinfo($fileName, PATHINFO_FILENAME);
                $dt = Carbon::now('Asia/Ho_Chi_Minh');
                $path = env('MEDIA_ROOT_FOLDER', 'media') . '/' . $dt->format('Y') . '/' . $dt->format('m') . '/';
                $path_image = $path . $fileName;
                Storage::disk('s3')->put($path_image, file_get_contents($file), 'public');
                $imageExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                if (in_array($extension, $imageExtension)) {
                    $thumbnailImage = Image::make($file);
                    $thumbnailImage->fit(150, 150);
                    $resource = $thumbnailImage->stream();
                    $fileNameThumbnail = $fileNameNormal . '-150x150.' . $extension;
                    $path_thumbnail = $path . $fileNameThumbnail;
                    Storage::disk('s3')->put($path_thumbnail, $resource->__toString(), 'public');
                }
                Media::create([
                    'filename' => basename($path_image),
                    'url_folder' => $path,
                    'url_media' => $path_image,
                    'url_thumbnail' => isset($path_thumbnail) ? $path_thumbnail : "",
                    'owner_id' => (int)$request->user,
                    'extension' => $extension,
                ]);
            }
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user = User::findOrFail($id);
        // return response()->json(['data' => 'show'], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = Media::find($id);
        Storage::cloud()->delete($file->url_media);
        Storage::cloud()->delete($file->url_thumbnail);
        Storage::cloud()->delete($file->url_folder);
        $file->delete();
        return response()->json(['data' => 'delete'], 200);
    }


    public function download($id)
    {
        $attachment = Media::find($id);
        $extension = explode('.', $attachment->filename);
        $headers = [
            'Content-Type'        => 'application/' . $extension[1],
            'Content-Disposition' => 'attachment; filename="' . $attachment->filename . '"',
        ];
        return Storage::disk('s3')->download($attachment->url, null, $headers);
    }
}
