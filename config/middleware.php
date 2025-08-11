<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Aliases memetakan nama pendek ke kelas middleware. Digunakan saat
    | mendefinisikan middleware di routes atau controller.
    |
    */

    'middlewareAliases' => [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // ✅ Custom middleware
        'role.admin' => \App\Http\Middleware\CheckAdminRole::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Groups
    |--------------------------------------------------------------------------
    |
    | Middleware grup digunakan di route group seperti "web" atau "api".
    |
    */

    'middlewareGroups' => [
        'web' => [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ],

];
