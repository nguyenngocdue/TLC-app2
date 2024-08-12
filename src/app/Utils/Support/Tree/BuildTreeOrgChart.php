<?php

namespace App\Utils\Support\Tree;

use App\Utils\CacheToRamForThisSection;
use App\Utils\Support\CurrentUser;
use App\Utils\System\Timer;
use Illuminate\Support\Facades\DB;


class BuildTreeOrgChart extends BuildTree
{
    protected static $key = 'my_org_chart';
    protected static $fillableUser = [
        'id',
        'name0',
        'org_chart',
        'viewport_uids',
        'leaf_uids',
        'resigned',
        'show_on_beta',
        'show_on_org_chart',
        'time_keeping_type',
        'department',
        'workplace',
        'is_bod'
    ];
    protected static $tableDataRender = 'user_org_charts';
    protected static $keyTableQuery = 'org_chart';
}
