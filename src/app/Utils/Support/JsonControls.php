<?php

namespace App\Utils\Support;

use App\Utils\CacheToRamForThisSection;

class JsonControls
{
    private static $statuses_path = "configs/controls.json";
    private static function getAllExpensive()
    {
        $pathFrom = storage_path('json/' . self::$statuses_path);
        $json = json_decode(file_get_contents($pathFrom, true), true);
        return $json;
    }

    private static function getAll()
    {
        $key = "controls_json_of_the_app";
        return CacheToRamForThisSection::get($key, fn () => static::getAllExpensive());
    }

    public static function getViewAllEloquents()
    {
        return self::getAll()['view_all_eloquents'];
    }

    public static function getViewAllOracies()
    {
        return self::getAll()['view_all_oracies'];
    }

    public static function getPackages()
    {
        return self::getAll()['packages'];
    }
    public static function getBreadcrumbGroup()
    {
        return self::getAll()['breadcrumb_group'];
    }
    public static function getSubPackages()
    {
        return self::getAll()['sub_packages'];
    }

    public static function getManagePropEloquents()
    {
        return self::getAll()['manage_prop_eloquents'];
    }

    public static function getManagePropOracies()
    {
        return self::getAll()['manage_prop_oracies'];
    }

    public static function getControls()
    {
        return self::getAll()['controls'];
    }

    public static function getDateTimeControls()
    {
        return self::getAll()['datetime_controls'];
    }

    public static function getHeadings()
    {
        return self::getAll()['headings'];
    }

    public static function getRendererViewAll()
    {
        return self::getAll()['renderer_view_all'];
    }

    public static function getRendererEdit()
    {
        return self::getAll()['renderer_edit'];
    }

    // public static function getQrCodeApps()
    // {
    //     return self::getAll()['qr_code_apps'];
    // }

    public static function getAssignees()
    {
        return self::getAll()['assignees'];
    }
    public static function getMonitors()
    {
        return self::getAll()['monitors'];
    }
    public static function getUsers()
    {
        return self::getAll()['users'];
    }
    public static function getParamUnits()
    {
        return self::getAll()['param_units'];
    }
    public static function getIgnoreDuplicatable()
    {
        return self::getAll()['ignore_duplicatable'];
    }
    public static function getAppsHaveProjectColumn()
    {
        return self::getAll()['apps_have_project_column'];
    }
    public static function getAppsHaveSubProjectColumn()
    {
        return self::getAll()['apps_have_sub_project_column'];
    }
    public static function getAppsHaveDueDateColumn()
    {
        return self::getAll()['apps_have_due_date_column'];
    }
    public static function getAppsHaveQrApp()
    {
        return self::getAll()['apps_have_qr_app'];
    }
    public static function getAppsHaveViewAllCalendar()
    {
        return self::getAll()['apps_have_view_all_calendar'];
    }
    public static function getAppsHaveViewAllMatrix()
    {
        return self::getAll()['apps_have_view_all_matrix'];
    }
    public static function getAppsHaveViewAllMatrixPrint()
    {
        return self::getAll()['apps_have_view_all_matrix_print'];
    }
    public static function getAppsHaveViewAllMatrixSignature()
    {
        return self::getAll()['apps_have_view_all_matrix_signature'];
    }
    public static function getAppsHaveViewAllMatrixApproveMulti()
    {
        return self::getAll()['apps_have_view_all_matrix_approve_multi'];
    }
    public static function getAppsHaveViewAllKanban()
    {
        return self::getAll()['apps_have_view_all_kanban'];
    }
    public static function getAppsHaveAddNewByCloning()
    {
        return self::getAll()['apps_have_add_new_by_cloning'];
    }
    public static function getViewAllTabTaxonomy()
    {
        return self::getAll()['view_all_tab_taxonomy'];
    }
}
