<?php

namespace App\Providers;

use Core\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        foreach (glob(ROUTES . DS . '*' . EXT) as $filename) {
            require_once $filename;
        }
    }
}
