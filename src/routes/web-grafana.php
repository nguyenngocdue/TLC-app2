<?php

use App\Http\Controllers\DemoGrafanaController;
use App\Http\Controllers\WelcomeDueTestGrafanaController;
use Illuminate\Support\Facades\Route;


Route::get('test-grafana', [DemoGrafanaController::class, 'index']);
