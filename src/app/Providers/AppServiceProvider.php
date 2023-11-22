<?php

namespace App\Providers;

use App\Services\Comment\CommentService;
use App\Services\Comment\CommentServiceInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Hr_timesheet_line\Hr_timesheet_lineRepository;
use App\Repositories\Hr_timesheet_line\Hr_timesheet_lineRepositoryInterface;
use App\Repositories\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigRepository;
use App\Repositories\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigRepositoryInterface;
use App\Services\Hr_timesheet_line\Hr_timesheet_lineService;
use App\Services\Hr_timesheet_line\Hr_timesheet_lineServiceInterface;
use App\Services\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigService;
use App\Services\Qaqc_insp_chklst_sht_sig\Qaqc_insp_chklst_sht_sigServiceInterface;
use App\Utils\System\Memory;
use App\Utils\System\Timer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Channels\DatabaseChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // singleton Services
        $this->app->singleton(CommentServiceInterface::class, CommentService::class);
        $this->app->singleton(Qaqc_insp_chklst_sht_sigServiceInterface::class, Qaqc_insp_chklst_sht_sigService::class);
        $this->app->singleton(Hr_timesheet_lineServiceInterface::class, Hr_timesheet_lineService::class);


        // singleton Repositories
        $this->app->singleton(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->singleton(Qaqc_insp_chklst_sht_sigRepositoryInterface::class, Qaqc_insp_chklst_sht_sigRepository::class);
        $this->app->singleton(Hr_timesheet_lineRepositoryInterface::class, Hr_timesheet_lineRepository::class);


        // register packages
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel);
        // $this->app->when(MigrationRelationShipCreator::class)
        //     ->needs('$customStubPath')
        //     ->give(function ($app) {
        //         return $app->basePath('stubs');
        //     });
        // $this->app->when(CreateControllerEntityCreator::class)
        //     ->needs('$customStubPath')
        //     ->give(function ($app) {
        //         return $app->basePath('stubs');
        //     });
        // Model::preventLazyLoading(!app()->isProduction());
        Model::preventLazyLoading(env("DISABLED_LAZY_LOAD"));

        include_once(__DIR__ . "/Macro/Str.php");
        include_once(__DIR__ . "/Macro/StrDb.php");

        include_once(__DIR__ . "/Macro/Arr.php");
        include_once(__DIR__ . "/Macro/App.php");

        Timer::startTimeCounter();
        Memory::startMemoryCounter();
    }
}
