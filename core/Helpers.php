<?php
use Core\Router;

if (!function_exists('config')) {
    function config($key, $default = null)
    {
        return \Core\Bootstrap::getConfig($key, $default);
    }
}

if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }

        // Tırnakları temizle
        if (preg_match('/\A([\'"])(.*)\1\z/', $value, $matches)) {
            $value = $matches[2];
        }

        // Mantıksal değerleri dönüştür
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

if (!function_exists('e')) {
    function e($value)
    {
        if (is_array($value) || is_object($value)) {
            return htmlspecialchars(json_encode($value, JSON_UNESCAPED_UNICODE) ?: "");
        }
        return htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('route')) {
    function route($name, $params = [])
    {
        $routes = Router::getRoutes();
        $targetRoute = null;

        foreach ($routes as $route) {
            if ($route['name'] === $name) {
                $targetRoute = $route;
                break;
            }
        }

        if (!$targetRoute) {
            throw new Exception("Route name not found: {$name}");
        }

        $uri = $targetRoute['uri'];

        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }

        return url($uri);
    }
}

if (!function_exists('url')) {

    function url($path = "")
    {
        return config('app.base_url') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect($name, $params = [])
    {
        if (filter_var($name, FILTER_VALIDATE_URL)) {
            header("Location: " . $name);
        } else {
            header("Location: " . route($name, $params));
        }
        exit;
    }
}
