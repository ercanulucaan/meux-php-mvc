<?php

namespace Core;

class View
{
    protected static $_sections = [];
    protected static $_layout = null;
    protected static $_currentSection = null;
    protected static $_sharedData = [];
    protected static $_renderDepth = 0;

    public static function share($key, $value = null)
    {
        if (is_array($key)) {
            static::$_sharedData = array_merge(static::$_sharedData, $key);
        } else {
            static::$_sharedData[$key] = $value;
        }
    }

    public static function render($path, $data = [])
    {
        $_viewPath = str_replace('.', DS, $path);
        $_viewFile = VIEWS . DS . $_viewPath . EXT;

        if (!file_exists($_viewFile)) {
            throw new \Exception("View file not found: $_viewFile");
        }

        $_compiledFile = static::getCompiledPath($_viewFile);

        if (!file_exists($_compiledFile) || filemtime($_viewFile) > filemtime($_compiledFile)) {
            static::compileFile($_viewFile, $_compiledFile);
        }

        extract(array_merge(static::$_sharedData, $data));

        ob_start();
        try {
            include $_compiledFile;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        $_content = ob_get_clean();

        if (static::$_layout) {
            $_layoutPath = static::$_layout;
            static::$_layout = null;
            return static::render($_layoutPath, array_merge($data, ['content' => $_content]));
        }

        echo $_content;
    }

    protected static function compileFile($viewFile, $compiledFile)
    {
        $content = file_get_contents($viewFile);
        $compiled = static::compile($content);

        if (!is_dir(dirname($compiledFile))) {
            mkdir(dirname($compiledFile), 0777, true);
        }

        file_put_contents($compiledFile, $compiled);
    }

    protected static function getCompiledPath($viewFile)
    {
        $hash = md5($viewFile);
        return VIEW_CACHE . DS . $hash . EXT;
    }

    protected static function compile($code)
    {
        // 1. Comments {{-- comment --}}
        $code = preg_replace('/\{\{--(.+?)--\}\}/s', '', $code);

        // 2. Escaped output {{ $var }}
        $code = preg_replace('/\{\{\s*(.+?)\s*\}\}/s', '<?php echo e($1 ?? ""); ?>', $code);

        // 3. Raw output {!! $var !!}
        $code = preg_replace('/\{!!\s*(.+?)\s*!!\}/s', '<?php echo $1 ?? ""; ?>', $code);

        // 4. Control Structures
        $directives = [
            '/@if\s*\((.*)\)/' => '<?php if($1): ?>',
            '/@elseif\s*\((.*)\)/' => '<?php elseif($1): ?>',
            '/@else/' => '<?php else: ?>',
            '/@endif/' => '<?php endif; ?>',
            '/@unless\s*\((.*)\)/' => '<?php if(!($1)): ?>',
            '/@endunless/' => '<?php endif; ?>',
            '/@auth/' => '<?php if(\Core\Session::has("user_id")): ?>',
            '/@endauth/' => '<?php endif; ?>',
            '/@guest/' => '<?php if(!\Core\Session::has("user_id")): ?>',
            '/@endguest/' => '<?php endif; ?>',
            '/@foreach\s*\((.*)\)/' => '<?php foreach($1): ?>',
            '/@endforeach/' => '<?php endforeach; ?>',
            '/@forelse\s*\(\s*(.+?)\s+as\s+(.+?)\s*\)/' => '<?php if(isset($1) && count($1) > 0): foreach($1 as $2): ?>',
            '/@empty/' => '<?php endforeach; else: ?>',
            '/@endforelse/' => '<?php endif; ?>',
            '/@php/' => '<?php ',
            '/@endphp/' => ' ?>',
        ];

        foreach ($directives as $pattern => $replacement) {
            $code = preg_replace($pattern, $replacement, $code);
        }

        $code = preg_replace('/@extends\(\'(.+?)\'\)/', '<?php \Core\View::extend(\'$1\'); ?>', $code);
        $code = preg_replace('/@yield\(\'(.+?)\'\)/', '<?php echo \Core\View::yieldSection(\'$1\'); ?>', $code);
        $code = preg_replace('/@section\(\'(.+?)\'\,\s*\'(.+?)\'\)/', '<?php \Core\View::startSection(\'$1\', \'$2\'); ?>', $code);
        $code = preg_replace('/@section\(\'(.+?)\'\)/', '<?php \Core\View::startSection(\'$1\'); ?>', $code);
        $code = preg_replace('/@endsection/', '<?php \Core\View::endSection(); ?>', $code);

        return $code;
    }

    public static function extend($path)
    {
        static::$_layout = $path;
    }

    public static function startSection($name, $content = null)
    {
        if ($content !== null) {
            static::$_sections[$name] = $content;
        } else {
            ob_start();
            static::$_currentSection = $name;
        }
    }

    public static function endSection()
    {
        if (static::$_currentSection) {
            static::$_sections[static::$_currentSection] = ob_get_clean();
            static::$_currentSection = null;
        }
    }

    public static function yieldSection($name)
    {
        return static::$_sections[$name] ?? '';
    }
}
