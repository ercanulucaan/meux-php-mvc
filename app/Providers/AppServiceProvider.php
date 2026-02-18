<?php

namespace App\Providers;

use Core\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Timezone ayarı
        date_default_timezone_set(config('app.timezone', 'UTC'));
    }
}
