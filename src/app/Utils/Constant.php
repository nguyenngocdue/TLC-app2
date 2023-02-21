<?php

namespace App\Utils;

class Constant
{
    /** ATTACHMENT */
    const ORPHAN_MEDIA = "ORPHAN_MEDIA_014";
    const PATH = "env('AWS_ENDPOINT') . '/' . env('AWS_BUCKET') . '/'";

    /** DATE TIME FORMAT */
    const FORMAT_DATE_MYSQL = "Y-m-d";
    const FORMAT_DATE_ASIAN = "d/m/Y";
    const FORMAT_DATE_US = "m/d/Y";

    const FORMAT_DATETIME_MYSQL = "Y-m-d H:i:s";
    const FORMAT_DATETIME_ASIAN = "d/m/Y H:i";
    const FORMAT_DATETIME_US = "m/d/Y H:i:s";


    const FORMAT_TIME_MYSQL = "H:i:s";
    const FORMAT_TIME_ASIAN = "H:i";
    const FORMAT_TIME_US = "H:i";

    const FORMAT_WEEKDAY_SHORT = "D";
    const FORMAT_YEAR_MONTH = "Y/m";
}
