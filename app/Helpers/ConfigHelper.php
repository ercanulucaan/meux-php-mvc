<?php
use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Veritabanından (settings tablosu) ayar değerini getirir.
     */
    function setting($key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Tüm veritabanı ayarlarını döndürür.
     */
    function settings()
    {
        $settings = Setting::all();
        $config = [];

        foreach ($settings as $setting) {
            $config[$setting['key']] = $setting['value'];
        }

        return $config;
    }
}
