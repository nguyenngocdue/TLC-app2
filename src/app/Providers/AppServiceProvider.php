<?php

namespace App\Providers;

use App\Console\CreateControllerEntity\CreateControllerEntityCreator;
use App\Console\CreateTableRelationship\MigrationRelationShipCreator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->when(MigrationRelationShipCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });
        $this->app->when(CreateControllerEntityCreator::class)
            ->needs('$customStubPath')
            ->give(function ($app) {
                return $app->basePath('stubs');
            });

        Str::macro('modelToPretty', function (string $string) {
            return Str::headline(App::make($string)->getTable());
        });
        Str::macro('same', function (string $string) {
            return $string;
        });
        Str::macro('makeId', function (string $id) {
            $numberRender = str_pad($id, 6, '0', STR_PAD_LEFT);
            $result = '#' . substr($numberRender, 0, 3) . '.' . substr($numberRender, 3, 6);
            return $result;
        });
        Str::macro('limitWords', function (string $str, $count) {
            $i = $c = 0;
            while ($i < strlen($str)) {
                if ($str[$i] === ' ' && ++$c === $count) return substr($str, 0, $i) . " ...";
                $i++;
            }
            return $str;
        });
        Arr::macro('moveDirection', function ($json, $direction, $index, $name = null) {
            switch ($direction) {
                case "up":
                    if ($index === 0) {
                        $value = $json[0];
                        unset($json[0]);
                        array_push($json, $value);
                    } else {
                        $tmp = $json[$index - 1];
                        $json[$index - 1] = $json[$index];
                        $json[$index] = $tmp;
                    }
                    break;
                case "down":
                    if ($index === sizeof($json) - 1) {
                        $value = array_pop($json);
                        array_unshift($json, $value);
                    } else {
                        $tmp = $json[$index + 1];
                        $json[$index + 1] = $json[$index];
                        $json[$index] = $tmp;
                    }
                    break;
                case "left":
                    if (!is_null($name)) {
                        array_push($json, $name);
                    }
                    break;
                case "right":
                    if (!is_null($name)) {
                        $json = array_filter($json, fn ($name0) => $name !== $name0);
                    }
                    break;
                case "right_by_name":
                    if (!is_null($name)) {
                        $json = array_filter($json, fn ($item) => $name !== $item['name']);
                    }
                    break;
            }
            return $json;
        });
    }
}
