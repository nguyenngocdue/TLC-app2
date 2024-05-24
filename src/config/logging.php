<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    "default" => env("LOG_CHANNEL", "stack"),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    "deprecations" => env("LOG_DEPRECATIONS_CHANNEL", "null"),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    "channels" => [
        "stack" => [
            "driver" => "stack",
            "channels" => ["single", "daily"], //log logging 
            "ignore_exceptions" => false,
        ],

        "single" => [
            "driver" => "single",
            "path" => storage_path("logs/laravel.log"),
            "level" => env("LOG_LEVEL", "debug"),
        ],

        "daily" => [
            "driver" => "daily",
            "path" => storage_path("logs/daily/laravel.log"),
            "level" => env("LOG_LEVEL", "debug"),
            "days" => 14, //Default 14
        ],

        "slack" => [
            "driver" => "slack",
            "url" => env("LOG_SLACK_WEBHOOK_URL"),
            "username" => "Laravel Log",
            "emoji" => ":boom:",
            "level" => env("LOG_LEVEL", "critical"),
        ],

        "papertrail" => [
            "driver" => "monolog",
            "level" => env("LOG_LEVEL", "debug"),
            "handler" => env("LOG_PAPERTRAIL_HANDLER", SyslogUdpHandler::class),
            "handler_with" => [
                "host" => env("PAPERTRAIL_URL"),
                "port" => env("PAPERTRAIL_PORT"),
                "connectionString" =>
                "tls://" .
                    env("PAPERTRAIL_URL") .
                    ":" .
                    env("PAPERTRAIL_PORT"),
            ],
        ],

        "stderr" => [
            "driver" => "monolog",
            "level" => env("LOG_LEVEL", "debug"),
            "handler" => StreamHandler::class,
            "formatter" => env("LOG_STDERR_FORMATTER"),
            "with" => [
                "stream" => "php://stderr",
            ],
        ],

        "syslog" => [
            "driver" => "syslog",
            "level" => env("LOG_LEVEL", "debug"),
        ],

        "errorlog" => [
            "driver" => "errorlog",
            "level" => env("LOG_LEVEL", "debug"),
        ],

        "null" => [
            "driver" => "monolog",
            "handler" => NullHandler::class,
        ],

        "emergency_channel" => [
            'driver' => 'single',
            "path" => storage_path("logs/emergency/emergency.log"),
            'level' => 'debug',
        ],

        "cleanup_trash_channel" => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/cleanup_trash/trashed.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],

        'mail_log_channel' => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/mail/mail.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],

        'schedule_heartbeat_channel' => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/schedule/schedule_heartbeat.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],
        'schedule_diginet_sync_channel' => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/schedule/schedule_diginet_sync.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],
        'schedule_cleanup_trash_channel' => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/schedule/schedule_cleanup_trash.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],
        'schedule_remind_signoff_channel' => [
            'driver' => 'daily', // or 'single' for a single log file
            'path' => storage_path('logs/schedule/schedule_remind_signoff.log'), // Define the log storage location
            'level' => 'debug', // Define the log level
            'days' => 30, // Number of days log files should be retained
        ],
    ],
];
