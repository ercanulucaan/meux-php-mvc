<?php

return [
    'name' => env('APP_NAME', 'Micro MVC'),
    'base_url' => env('APP_URL', 'http://localhost'),
    'debug' => env('APP_DEBUG', true),
    'timezone' => env('APP_TIMEZONE', 'Europe/Istanbul'),
    'providers' => [
        App\Providers\AppServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\ViewServiceProvider::class,
    ]
];