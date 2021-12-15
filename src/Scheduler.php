<?php

namespace AwemaPL\Scheduler;

use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use AwemaPL\Scheduler\Contracts\Scheduler as SchedulerContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Scheduler implements SchedulerContract
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;}

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveAdminInstallationRoutes() && (!$this->isMigrated())) {
                $this->adminInstallationRoutes();
            }
            if ($this->isActiveUserScheduleRoutes()) {
                $this->userScheduleRoutes();
            }
        }
    }

    /**
     * Admin installation routes
     */
    protected function adminInstallationRoutes()
    {
        $prefix = config('scheduler.routes.admin.installation.prefix');
        $namePrefix = config('scheduler.routes.admin.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Scheduler\Admin\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Scheduler\Admin\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * User schedule routes
     */
    protected function userScheduleRoutes()
    {
        $prefix = config('scheduler.routes.user.schedule.prefix');
        $namePrefix = config('scheduler.routes.user.schedule.name_prefix');
        $middleware = config('scheduler.routes.user.schedule.middleware');

        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@store')
                ->name('store');
            $this->router
                ->get('/accounts', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@delete')
                ->name('delete');
            $this->router
                ->get('/select-schedulable-type', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@selectSourceableType')
                ->name('select_schedulable_type');
            $this->router
                ->get('/select-schedulable-id', '\AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers\ScheduleController@selectSourceableId')
                ->name('select_schedulable_id');
        });
    }

    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveAdminInstallationRoutes()
            && $canForPermission
            && (!$this->isMigrated());
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = \DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $tables = array_values(config('scheduler.database.tables'));
        foreach ($tables as $table){
            if (!in_array($table, $tablesInDb)){
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('scheduler.routes.active');
    }

    /**
     * Is active admin installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveAdminInstallationRoutes()
    {
        return config('scheduler.routes.admin.installation.active');
    }


    /**
     * Is active user schedule routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserScheduleRoutes()
    {
        return config('scheduler.routes.user.schedule.active');
    }

    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(Translator::class)->get('scheduler::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }

        if ($user = request()->user() ?? null){
            return $user->can(config('scheduler.installation.auto_redirect.permission'));
        }

        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()){
            $schedulerMenu = config('scheduler-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $schedulerMenu);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('scheduler-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute package migrations
     */
    public function migrate()
    {
         Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-scheduler/database/migrations']);
    }

    /**
     * Install package
     */
    public function install()
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
       if ($this->canMergePermissions()){
           $schedulerPermissions = config('scheduler.permissions');
           $tempPermissions = config('temp_permission.permissions', []);
           $permissions = array_merge_recursive($tempPermissions, $schedulerPermissions);
           config(['temp_permission.permissions' => $permissions]);
       }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('scheduler.merge_permissions');
    }
}
