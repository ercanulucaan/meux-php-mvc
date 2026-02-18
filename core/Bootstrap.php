<?php

namespace Core;

class Bootstrap
{
    protected static $_config = [];
    protected static $_providerInstances = [];

    public static function init()
    {
        define('EXT', '.php');
        define('DS', DIRECTORY_SEPARATOR);
        define('ROOT', dirname(__DIR__));
        define('APP', ROOT . DS . 'app');
        define('CORE', ROOT . DS . 'core');
        define('CONFIG', ROOT . DS . 'config');
        define('ROUTES', ROOT . DS . 'routes');
        define('VIEWS', APP . DS . 'views');
        define('STORAGE', ROOT . DS . 'storage');
        define('VIEW_CACHE', STORAGE . DS . 'views');

        static::loadEnv();
        static::loadHelpers();
        static::registerAutoloader();
        static::loadConfigs();

        static::initializeProviders();

        Session::init();
        ErrorHandler::register();
        static::ensureDirectories();

        Router::run();
    }

    protected static function loadEnv()
    {
        $path = ROOT . DS . '.env';
        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0)
                    continue;
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);

                // Tırnakları temizle
                if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
                    $value = $matches[2];
                }

                if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                }
            }
        }
    }

    protected static function registerAutoloader()
    {
        spl_autoload_register(function ($class) {
            $standardPath = str_replace('\\', DS, $class);

            if (strpos($class, 'Core\\') === 0) {
                $standardPath = str_replace('Core' . DS, 'core' . DS, $standardPath);
            } elseif (strpos($class, 'App\\') === 0) {
                $standardPath = str_replace('App' . DS, 'app' . DS, $standardPath);
            }

            $file = ROOT . DS . $standardPath . EXT;
            if (file_exists($file)) {
                require_once $file;
                return;
            }

            $subdirs = ['Controllers', 'Models', 'Helpers', 'Core', 'Middlewares'];
            $className = basename(str_replace('\\', DS, $class));

            foreach ($subdirs as $subdir) {
                $file = APP . DS . $subdir . DS . $className . EXT;
                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }

    protected static function ensureDirectories()
    {
        if (!is_dir(STORAGE)) {
            mkdir(STORAGE, 0777, true);
        }
        if (!is_dir(VIEW_CACHE)) {
            mkdir(VIEW_CACHE, 0777, true);
        }
    }

    protected static function loadConfigs()
    {
        foreach (glob(CONFIG . DS . '*' . EXT) as $file) {
            $key = basename($file, EXT);
            static::$_config[$key] = require $file;
        }
    }

    protected static function loadHelpers()
    {
        require_once CORE . DS . 'Helpers' . EXT;
        $appHelpers = ROOT . DS . 'app' . DS . 'Helpers';
        if (is_dir($appHelpers)) {
            foreach (glob($appHelpers . DS . '*' . EXT) as $helper) {
                require_once $helper;
            }
        }
    }

    protected static function initializeProviders()
    {
        $providers = static::getConfig('app.providers', []);

        foreach ($providers as $providerClass) {
            $provider = new $providerClass();
            $provider->register();
            static::$_providerInstances[] = $provider;
        }

        foreach (static::$_providerInstances as $provider) {
            $provider->boot();
        }
    }


    public static function getConfig($key, $default = null)
    {
        $parts = explode('.', $key);
        $config = static::$_config;

        foreach ($parts as $part) {
            if (!isset($config[$part])) {
                return $default;
            }
            $config = $config[$part];
        }

        return $config;
    }
}
