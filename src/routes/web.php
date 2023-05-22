<?php

use App\Utils\System\Timer;
use Illuminate\Support\Facades\Auth;

Timer::startTimeCounter();
Auth::routes();

include_once(__DIR__ . "/web-backward-compatible.php");
include_once(__DIR__ . "/web-manage-workflows.php");
include_once(__DIR__ . "/web-manage-libs.php");
include_once(__DIR__ . "/web-dashboard.php");
include_once(__DIR__ . "/web-global.php");
include_once(__DIR__ . "/web-guest.php");
include_once(__DIR__ . "/web-permission.php");
include_once(__DIR__ . "/web-report.php");
