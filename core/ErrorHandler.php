<?php

namespace Core;

class ErrorHandler
{
    protected static $debug = false;

    public static function register()
    {
        static::$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';

        error_reporting(E_ALL);
        set_error_handler([static::class, 'handleError']);
        set_exception_handler([static::class, 'handleException']);
    }

    public static function handleError($level, $message, $file, $line)
    {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function handleException($exception)
    {
        $code = $exception->getCode();
        if ($code != 404) {
            http_response_code(500);
        }

        static::renderErrorPage($exception);
    }

    protected static function renderErrorPage($exception)
    {
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        echo '
        <!DOCTYPE html>
        <html lang="tr">
        <head>
            <meta charset="UTF-8">
            <title>Sistem Hatası</title>
            <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;600&display=swap" rel="stylesheet">
            <style>
                :root { --primary: #ef4444; --bg: #0f172a; --card: #1e293b; --text: #f8fafc; }
                body { margin: 0; padding: 2rem; font-family: "Outfit", sans-serif; background: var(--bg); color: var(--text); display: flex; justify-content: center; align-items: flex-start; min-height: 100vh; line-height: 1.6; }
                .container { width: 100%; max-width: 900px; }
                .card { background: var(--card); border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.3); border-top: 5px solid var(--primary); }
                h1 { margin-top: 0; font-size: 2rem; color: var(--primary); }
                .msg { font-size: 1.25rem; background: rgba(239, 68, 68, 0.1); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--primary); margin-bottom: 2rem; }
                .debug-info { background: #000; padding: 1.5rem; border-radius: 8px; overflow-x: auto; font-family: monospace; font-size: 0.9rem; color: #10b981; }
                .label { display: block; font-weight: 600; margin-bottom: 0.5rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 1.5rem; }
                .footer { margin-top: 2rem; text-align: center; opacity: 0.5; font-size: 0.8rem; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="card">
                    <h1>Sistem Hatası (500)</h1>
                    <div class="msg">' . htmlspecialchars($message ?? '') . '</div>

                    ' . (static::$debug ? '
                        <span class="label">Dosya ve Satır</span>
                        <div class="debug-info">' . $file . ' on line ' . $line . '</div>

                        <span class="label">Stack Trace</span>
                        <pre class="debug-info">' . htmlspecialchars($trace ?? '') . '</pre>
                    ' : '<p>Üzgünüz, bir şeyler ters gitti. Lütfen daha sonra tekrar deneyiniz.</p>') . '
                </div>
                <div class="footer">Antigravity MVC Framework v1.0</div>
            </div>
        </body>
        </html>';
        exit;
    }
}
