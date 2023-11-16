<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Utils\Constant;
use App\Utils\Storage\Thumbnail;
use App\Utils\Support\CurrentUser;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ThumbnailController extends Controller
{
    private $view_create_thumbnail = 'view_create_thumbnail';
    public function getType()
    {
        return 'dashboard';
    }
    public function index()
    {
        $settingsViewCreateThumbnail = CurrentUser::getSettings()[$this->view_create_thumbnail] ?? [];
        return view('create-thumbnail', [
            'settings' => $settingsViewCreateThumbnail,
        ]);
    }
    public function create(Request $request)
    {
        if (!$request->height && !$request->width) {
            Toastr::warning("Please fill input Height or Width", 'Create Thumbnail on S3 Minio');
            return redirect()->back();
        };
        try {
            $values = ['height' => $request->height, 'width' => $request->width, 'position' => $request->position];
            $this->saveUserSettingForCreateThumbnail($values);
            $results = Thumbnail::createThumbnailByOptions($request->height, $request->width, $request->position);
            if ($request == true) {
                Toastr::success('Create thumbnails all files successfully', 'Create Thumbnail on S3 Minio');
                $this->saveUserSettingForCreateThumbnail($values);
                return redirect()->back();
            } else {
                Toastr::warning($results, 'Create Thumbnail on S3 Minio');
            }
        } catch (\Throwable $th) {
            Toastr::error($th->getMessage(), 'Create Thumbnail on S3 Minio');
        }
    }
    private function saveUserSettingForCreateThumbnail($values)
    {
        $user = CurrentUser::get();
        $settings = $user->settings;
        $settings[$this->view_create_thumbnail] = $values;
        $user->settings = $settings;
        $user->update();
    }
}
