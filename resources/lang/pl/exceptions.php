<?php

return [
    'admin' =>[

    ],
    'user' =>[
        'schedule' =>[
            'error_request_scheduler_api' => 'Błąd zapytania do API Scheduler. :error',
            'error_incompatibile_version' => 'Błąd Scheduler. Niekompatybilna wersja (minimalna wersja: :min, maksymalna wersja: :max).',
            'http_xml_response_is_not_parsable' => 'Błąd Scheduler. Odpowiedź HTTP XML nie jest analizowalna.',
            'http_response_is_empty' =>'Błąd Scheduler. Odpowiedź HTTP jest pusta.',
            'bad_parameters_given' =>'Podano niepoprawne parametry.',
            'api_is_disabled' =>'Interfejs API Scheduler jest wyłączony. Proszę go aktywować w panelu administratora Scheduler.',
            'invalid_api_key_format' => 'Błąd API Scheduler. Niepoprawny format klucza API.',
            'api_key_is_not_active'=> 'Błąd API Scheduler. Klucz API jest nieaktywny.',
            'no_permission_api_key' =>'Błąd API Scheduler. Brak uprawnień. Proszę ustawić uprawnienia w panelu administratora Scheduler.'
        ]
    ],
];
