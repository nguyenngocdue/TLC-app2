<?php

use App\Utils\System\Memory;
use App\Utils\System\Timer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Timer::startTimeCounter();
Memory::startMemoryCounter();

include_once(__DIR__ . "/api-auth.php");
include_once(__DIR__ . "/api-entity.php");
include_once(__DIR__ . "/api-hr.php");
include_once(__DIR__ . "/api-prod.php");
include_once(__DIR__ . "/api-qaqc.php");
include_once(__DIR__ . "/api-system.php");
