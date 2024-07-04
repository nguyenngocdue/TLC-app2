<?php

namespace App\Utils;

class Constant
{
    /** DATE TIME FORMAT */
    const FORMAT_DATE_MYSQL = "Y-m-d";
    const FORMAT_DATE_ASIAN = "d/m/Y";
    const FORMAT_DATE_US = "m/d/Y";

    const FORMAT_DATETIME_MYSQL = "Y-m-d H:i:s";
    const FORMAT_DATETIME_ASIAN = "d/m/Y H:i";
    const FORMAT_DATETIME_US = "m/d/Y H:i:s";


    const FORMAT_TIME_MYSQL = "H:i:s";
    const FORMAT_TIME = "H:i";

    const FORMAT_WEEKDAY_SHORT = "D";
    const FORMAT_WEEK = "W/Y";
    const FORMAT_MONTH = "m/Y";
    const FORMAT_QUARTER = "Qq/Y";
    const FORMAT_YEAR = "Y";

    const FORMAT_YEAR_MONTH = "Y/m"; //<< Please do not change this format, for create Media Folder
    const FORMAT_YEAR_MONTH0 = "Y-m"; //<< Please do not change this format, for SQL Comparison

    const GLOBAL = 'global';
    const VIEW_ALL = 'view_all';
    const VIEW_EDIT = 'view_edit';
    const VIEW_ORG_CHART = 'view_org_chart';

    const DASHBOARD_PROJECT_CLIENT = "-project-client";
    const DASHBOARD_EXTERNAL_INSPECTOR = "-external-inspector";
    const DASHBOARD_APARTMENT_OWNER = "-apartment-owner";
    const DASHBOARD_COUNCIL_MEMBER = "-council-member";
    const DASHBOARD_NEWCOMER = "-newcomer";

    const EXTENSIONS_OF_FILE_GALLERY = ['mp4', 'MP4', 'mov', 'MOV', 'png', 'jpeg', 'gif', 'jpg', 'pdf'];


    const COLOR_PUBLIC_HOLIDAY = ["teal", "cyan", "yellow", "blue", "pink", "violet", "green"];
    const COLOR_PUBLIC_HOLIDAY2 = ["#5eead4", "#67e8f9", "#fde047", "#93c5fd", "#f9a8d4", "#c4b5fd", "#86efac"];

    const ONLY_VIDEOS = "mp4,mov";

    const ARRAY_ONLY_IMAGES = ["jpeg", "png", "jpg", "gif"];
    const ONLY_IMAGES = "png,jpeg,gif,jpg";
    // const ONLY_IMAGES = join(",", self::ARRAY_ONLY_IMAGES);

    const ARRAY_ONLY_NONE_MEDIA = ["csv", "pdf", "zip", "docx"];
    const ONLY_NONE_MEDIA = "csv,pdf,zip,docx";
    // const ONLY_NONE_MEDIA = join(",", self::ARRAY_ONLY_NONE_MEDIA);
}
