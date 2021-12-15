<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [

        // all routes is active
        'active' => true,

        // Administrator section.
        'admin' => [
            // section installations
            'installation' => [
                'active' => true,
                'prefix' => '/installation/scheduler',
                'name_prefix' => 'scheduler.admin.installation.',
                // this routes has beed except for installation module
                'expect' => [
                    'module-assets.assets',
                    'scheduler.admin.installation.index',
                    'scheduler.admin.installation.store',
                ]
            ],
        ],

        // User section
        'user' => [
            'schedule' => [
                'active' => true,
                'prefix' => '/scheduler/schedules',
                'name_prefix' => 'scheduler.user.schedule.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages', 'manage_scheduler_schedules',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' => 'users',
            'scheduler_schedules' =>'scheduler_schedules',
        ]
    ],

];
