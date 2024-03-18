<?php

use App\Models\Project;
use App\Utils\Support\CurrentUser;
use Illuminate\Support\Facades\App;

//isLocal --> laravel has this function by default

App::macro('isTesting', function () {
    return env('APP_ENV') == "testing";
});
App::macro('isProduction', function () {
    return env('APP_ENV') == "production";
});

App::macro('present', function () {
    return App::isTesting() || App::isLocal() || CurrentUser::isAdmin();
});

App::macro('backgroundImage', function () {
    $images = [];
    $pathMinio = app()->pathMinio();
    foreach (Project::all() as $project) {
        $attachment = $project->getAvatar;
        if(isset($attachment['url_media'])) $images[] = $pathMinio . $attachment['url_media'];
       
    }
    return $images;
});

App::macro('pathMinio', function () {
    return env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/';
});
App::macro('textBanner', function () {
    return [
        "Build Smarter, Not Harder: Modular Management at Your Fingertips.",
        "Where Every Module Fits Perfectly: Streamline Your Construction.",
        "Seamless Integration, Infinite Possibilities: Your Construction, Elevated.",
        "Innovate, Integrate, Inspire: The New Era of Construction Management.",
        "Design, Construct, Manage: All in One Modular Software.",
    ];
});