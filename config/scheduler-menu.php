<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[],
        'adminSidebar' =>[
            [
                'name' => 'Scheduler',
                'link' => '/scheduler/schedules',
                'icon' => 'data-time',
                'permissions' => 'manage_scheduler_schedules',
                'key' => 'scheduler::menus.schedules',
                'children_top' => [
                    [
                        'name' => 'Schedules',
                        'link' => '/scheduler/schedules',
                        'key' => 'scheduler::menus.schedules',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Schedules',
                        'link' => '/scheduler/schedules',
                        'key' => 'scheduler::menus.schedules',
                    ],
                ],
            ]
        ]
    ]
];
