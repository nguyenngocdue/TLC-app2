<?php

namespace Fortune\FontBeauty;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class FontBeautyServiceProvider extends ServiceProvider
{
    /**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/fonts' => public_path('vendor/font-awesome-pro-6'),], 'public');
    }
}
