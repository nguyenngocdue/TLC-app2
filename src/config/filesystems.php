<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    "default" => env("FILESYSTEM_DISK", "local"),
    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    "disks" => [
        "local" => [
            "driver" => "local",
            "root" => storage_path("app"),
        ],
        "json" => [
            "driver" => "local",
            "root" => storage_path("json"),
        ],
        "public" => [
            "driver" => "local",
            "root" => storage_path("app/public"),
            "url" => env("APP_URL") . "/storage",
            "visibility" => "public",
        ],
        "s3" => [
            "driver" => "s3",
            "key" => env("AWS_ACCESS_KEY_ID", "your AWS server key"),
            "secret" => env("AWS_SECRET_ACCESS_KEY", "your AWS server secret"),
            "region" => env("AWS_DEFAULT_REGION", "your AWS server secret"),
            "bucket" => env("AWS_BUCKET", "your AWS bucket name"),
            'url' => env('AWS_URL'),
            "endpoint" => env("AWS_ENDPOINT", "http://localhost:9000"),
            "use_path_style_endpoint" => env(
                "AWS_USE_PATH_STYLE_ENDPOINT",
                false,
            ),
            // "schema" => 'http',
            // 'options' => [
            //     'verify' => false,
            //     'curl_options' => [
            //         "CURLOPT_SSL_VERIFYHOST" => 0,
            //         "CURLOPT_SSL_VERIFYPEER" => 0,
            //     ]
            // ],
        ],
        // "project_plans" => [
        //     "driver" => "s3",
        //     "key" => env("AWS_ACCESS_KEY_ID", "your AWS server key"),
        //     "secret" => env("AWS_SECRET_ACCESS_KEY", "your AWS server secret"),
        //     "region" => env("AWS_DEFAULT_REGION", "your AWS server secret"),
        //     "bucket" => 'project-drawings',
        //     // "bucket" => env("AWS_BUCKET", "your AWS bucket name"),
        //     'url' => env('AWS_URL'),
        //     "endpoint" => env("AWS_ENDPOINT", "http://localhost:9000"),
        //     "use_path_style_endpoint" => env(
        //         "AWS_USE_PATH_STYLE_ENDPOINT",
        //         false,
        //     ),
        // ],
        // "conqa_backup" => [
        //     "driver" => "s3",
        //     "key" => env("AWS_ACCESS_KEY_ID", "your AWS server key"),
        //     "secret" => env("AWS_SECRET_ACCESS_KEY", "your AWS server secret"),
        //     "region" => env("AWS_DEFAULT_REGION", "your AWS server secret"),
        //     "bucket" => 'conqa-backup',
        //     // "bucket" => env("AWS_BUCKET", "your AWS bucket name"),
        //     'url' => env('AWS_URL'),
        //     "endpoint" => env("AWS_ENDPOINT", "http://localhost:9000"),
        //     "use_path_style_endpoint" => env(
        //         "AWS_USE_PATH_STYLE_ENDPOINT",
        //         false,
        //     ),
        // ],
        // "conqa_attachment" => [
        //     "driver" => "local",
        //     "root" => storage_path("database_attachments"),
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    "links" => [
        public_path("storage") => storage_path("app/public"),
    ],
];
