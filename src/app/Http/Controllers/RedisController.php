<?php

namespace App\Http\Controllers;

class RedisController extends Controller
{
    public function index()
    {
        dump(env("CACHE_DRIVER", "file"));
    }
}
