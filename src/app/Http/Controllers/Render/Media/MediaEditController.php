<?php

namespace App\Http\Controllers\Render\Media;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\EditController;
use App\Models\Media;

class MediaEditController extends EditController
{
   protected $type = "media";
    protected $data = Media::class;
}
