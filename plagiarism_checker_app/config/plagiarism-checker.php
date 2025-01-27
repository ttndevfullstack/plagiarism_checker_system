<?php

return [

    'available_locales' => ['en', 'vi'],

    'roles' => ['admin', 'teacher', 'student'],
    
    'permissions' => ['view', 'create', 'update', 'delete'],

    'panels' => [

        'admin'  => [
            'id' => 'admin',
            'path' => 'admin',
            'routes' => [
                'login' => 'filament.admin.auth.login',
                'dashboard' => 'filament.admin.pages.dashboard',
            ],
        ],
        
        'user'  => [
            'id' => 'user',
            'path' => 'user',
            'routes' => [
                'login' => 'filament.user.auth.login',
                'dashboard' => 'filament.user.pages.dashboard',
            ],
        ],

    ],

];
