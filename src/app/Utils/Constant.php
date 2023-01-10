<?php

namespace App\Utils;

class Constant
{
    /** ATTACHMENT */
    const ORPHAN_MEDIA = "ORPHAN_MEDIA_014";
    const PATH = "env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/'";

    /** DATE TIME FORMAT */
    const FORMAT_DATE_MYSQL = "Y-m-d";
    const FORMAT_DATETIME_MYSQL = "Y-m-d H:i:s";
    const FORMAT_TIME_MYSQL = "H:i:s";
    const FORMAT_WEEKDAY_SHORT = "D";
}
