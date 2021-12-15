<?php

namespace AwemaPL\Scheduler;


use AwemaPL\Scheduler\User\Sections\Schedules\Models\Schedule;
use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\Contracts\ScheduleRepository;
use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\EloquentScheduleRepository;
use AwemaPL\Scheduler\User\Sections\Schedules\Policies\SchedulePolicy;
use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Scheduler\Listeners\EventSubscriber;
use AwemaPL\Scheduler\Admin\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Scheduler\Admin\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Scheduler\Admin\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Scheduler\Admin\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Scheduler\Contracts\Scheduler as SchedulerContract;
use Illuminate\Support\Facades\Event;

class SchedulerServiceProvider extends AwemaProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Schedule::class => SchedulePolicy::class,
    ];

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'scheduler');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'scheduler');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->bootMiddleware();
        app('scheduler')->includeLangJs();
        app('scheduler')->menuMerge();
        app('scheduler')->mergePermissions();
        $this->registerPolicies();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/scheduler.php', 'scheduler');
        $this->mergeConfigFrom(__DIR__ . '/../config/scheduler-menu.php', 'scheduler-menu');
        $this->app->bind(SchedulerContract::class, Scheduler::class);
        $this->app->singleton('scheduler', SchedulerContract::class);
        $this->registerRepositories();
        $this->registerServices();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'scheduler';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(ScheduleRepository::class, EloquentScheduleRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('scheduler', RouteMiddleware::class);
    }

    /**
     * Boot grEloquentAccountRepositoryoup middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
    }
}
