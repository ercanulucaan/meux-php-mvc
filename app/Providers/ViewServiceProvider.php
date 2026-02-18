<?php

namespace App\Providers;

use Core\ServiceProvider;
use Core\View;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Tüm view'larda kullanılabilir global veriler
        View::share([
            'site_title' => setting('site_title', 'Micro MVC'),
            'settings' => settings(),
        ]);
    }
}
