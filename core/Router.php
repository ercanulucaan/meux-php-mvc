<?php

namespace Core;

class Router
{
    protected static $routes = [];
    protected static $groupAttributes = [];
    protected static $namedRoutes = [];

    public static function getRoutes()
    {
        return static::$routes;
    }


    public static function addRoute($methods, $uri, $callback)
    {
        $uri = static::prefixUri($uri);
        $methods = (array) $methods;

        $route = [
            'methods' => $methods,
            'uri' => $uri,
            'callback' => $callback,
            'middleware' => static::$groupAttributes['middleware'] ?? [],
            'name' => null
        ];

        static::$routes[] = &$route;

        return new class ($route) {
            protected $route;
            public function __construct(&$route)
            {
                $this->route = &$route; }
            public function name($name)
            {
                $this->route['name'] = $name;
                return $this; }
            public function middleware($middleware)
            {
                $this->route['middleware'] = array_merge($this->route['middleware'], (array) $middleware);
                return $this;
            }
        };
    }

    public static function get($uri, $callback)
    {
        return static::addRoute('GET', $uri, $callback);
    }
    public static function post($uri, $callback)
    {
        return static::addRoute('POST', $uri, $callback);
    }
    public static function put($uri, $callback)
    {
        return static::addRoute('PUT', $uri, $callback);
    }
    public static function delete($uri, $callback)
    {
        return static::addRoute('DELETE', $uri, $callback);
    }

    public static function group($attributes, $callback)
    {
        $previousGroupAttributes = static::$groupAttributes;

        if (isset($attributes['prefix'])) {
            $attributes['prefix'] = (isset($previousGroupAttributes['prefix']) ? $previousGroupAttributes['prefix'] : '') . '/' . trim($attributes['prefix'], '/');
        } else {
            $attributes['prefix'] = $previousGroupAttributes['prefix'] ?? '';
        }

        if (isset($attributes['middleware'])) {
            $attributes['middleware'] = array_merge($previousGroupAttributes['middleware'] ?? [], (array) $attributes['middleware']);
        } else {
            $attributes['middleware'] = $previousGroupAttributes['middleware'] ?? [];
        }

        static::$groupAttributes = $attributes;

        call_user_func($callback);

        static::$groupAttributes = $previousGroupAttributes;
    }

    protected static function prefixUri($uri)
    {
        $prefix = static::$groupAttributes['prefix'] ?? '';
        return '/' . trim(trim($prefix, '/') . '/' . trim($uri, '/'), '/');
    }

    public static function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $baseDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($baseDir !== '/' && $baseDir !== '' && $baseDir !== '.') {
            if (strpos($uri, $baseDir) === 0) {
                $uri = substr($uri, strlen($baseDir));
            }
        }

        $uri = '/' . trim($uri, '/');

        foreach (static::$routes as $route) {
            if (in_array($method, $route['methods'])) {
                $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^/]+)', $route['uri']);
                $pattern = "#^" . $pattern . "$#D";

                if (preg_match($pattern, $uri, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                    foreach ($route['middleware'] as $mw) {
                        if (!static::runMiddleware($mw))
                            return;
                    }

                    $callback = $route['callback'];
                    if (is_callable($callback)) {
                        return call_user_func_array($callback, $params);
                    }

                    if (is_array($callback)) {
                        $controller = new $callback[0]();
                        return call_user_func_array([$controller, $callback[1]], $params);
                    }
                }
            }
        }

        static::render404();
    }

    protected static function render404()
    {
        http_response_code(404);
        echo '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Sayfa Bulunamadı</title>
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;600&display=swap" rel="stylesheet">
            <style>
                :root {
                    --primary: #6366f1;
                    --bg: #0f172a;
                    --text: #f8fafc;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: "Outfit", sans-serif;
                    background: var(--bg);
                    color: var(--text);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 100vh;
                    overflow: hidden;
                }
                .container {
                    text-align: center;
                    position: relative;
                }
                .glitch {
                    font-size: 8rem;
                    font-weight: 800;
                    margin: 0;
                    text-transform: uppercase;
                    position: relative;
                    text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75),
                                -0.025em -0.05em 0 rgba(0, 255, 0, 0.75),
                                0.025em 0.05em 0 rgba(0, 0, 255, 0.75);
                    animation: glitch 500ms infinite;
                }
                @keyframes glitch {
                    0% { text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75), -0.05em -0.025em 0 rgba(0, 255, 0, 0.75), -0.025em 0.05em 0 rgba(0, 0, 255, 0.75); }
                    14% { text-shadow: 0.05em 0 0 rgba(255, 0, 0, 0.75), -0.05em -0.025em 0 rgba(0, 255, 0, 0.75), -0.025em 0.05em 0 rgba(0, 0, 255, 0.75); }
                    15% { text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75), 0.025em 0.025em 0 rgba(0, 255, 0, 0.75), -0.05em -0.05em 0 rgba(0, 0, 255, 0.75); }
                    49% { text-shadow: -0.05em -0.025em 0 rgba(255, 0, 0, 0.75), 0.025em 0.025em 0 rgba(0, 255, 0, 0.75), -0.05em -0.05em 0 rgba(0, 0, 255, 0.75); }
                    50% { text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75), 0.05em 0 0 rgba(0, 255, 0, 0.75), 0 -0.05em 0 rgba(0, 0, 255, 0.75); }
                    99% { text-shadow: 0.025em 0.05em 0 rgba(255, 0, 0, 0.75), 0.05em 0 0 rgba(0, 255, 0, 0.75), 0 -0.05em 0 rgba(0, 0, 255, 0.75); }
                    100% { text-shadow: -0.025em 0 0 rgba(255, 0, 0, 0.75), -0.025em -0.025em 0 rgba(0, 255, 0, 0.75), -0.025em -0.05em 0 rgba(0, 0, 255, 0.75); }
                }
                .desc {
                    font-size: 1.5rem;
                    opacity: 0.8;
                    margin-bottom: 2rem;
                }
                .btn {
                    display: inline-block;
                    padding: 0.8rem 2rem;
                    background: var(--primary);
                    color: white;
                    text-decoration: none;
                    border-radius: 50px;
                    font-weight: 600;
                    transition: 0.3s;
                    box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
                }
                .btn:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1 class="glitch">404</h1>
                <p class="desc">Aradığınız sayfa başka bir evrene gitmiş olabilir.</p>
                <a href="/" class="btn">Ana Sayfaya Dön</a>
            </div>
        </body>
        </html>';
    }

    protected static function runMiddleware($middleware)
    {
        if (class_exists($middleware)) {
            $mwInstance = new $middleware();
            return $mwInstance->handle();
        }
        return true;
    }
}
