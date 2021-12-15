<?php

return [
    'admin' =>[
        'installation' => [
            'attributes' =>[
            ],
            'messages' =>[

            ],
        ],
    ],
    'user' =>[
        'schedule'=>[
            'attributes' => [
                'name' => 'nazwa',
                'activated' =>'aktywny',
                'cron' =>'CRON',
            ],
            'messages'=>[
                'invalid_cron' =>'Nie poprawny format CRON.'
            ]
        ],
    ],

];
