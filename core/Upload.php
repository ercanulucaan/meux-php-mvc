<?php

namespace Core;

class Upload
{
    protected static $options = [
        'dir' => 'public/uploads',
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        'max_size' => 5242880, // 5MB
        'webp' => true,
        'quality' => 80,
        'encrypt_name' => true
    ];

    protected static $errors = [];

    /**
     * Set upload options
     */
    public static function options(array $opts)
    {
        static::$options = array_merge(static::$options, $opts);
    }

    public static function isMultiple($inputName)
    {
        return isset($_FILES[$inputName]) && is_array($_FILES[$inputName]['name']);
    }

    /**
     * Handle single file upload
     */
    public static function single($inputName)
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
            static::$errors[] = 'Yüklenecek dosya seçilmedi.';
            return false;
        }

        return static::process($_FILES[$inputName]);
    }

    /**
     * Handle multiple file uploads
     */
    public static function multiple($inputName)
    {
        if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName]['name'])) {
            static::$errors[] = 'Geçersiz çoklu yükleme isteği.';
            return false;
        }

        $results = [];
        $files = $_FILES[$inputName];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE)
                continue;

            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            $results[] = static::process($file);
        }

        return $results;
    }

    /**
     * Process the file (validation, moving, conversion)
     */
    protected static function process($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            static::$errors[] = "Yükleme hatası kodu: {$file['error']}";
            return false;
        }

        // Validate size
        if ($file['size'] > static::$options['max_size']) {
            static::$errors[] = 'Dosya boyutu çok büyük.';
            return false;
        }

        // Validate mime type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, static::$options['allowed_types'])) {
            static::$errors[] = "Geçersiz dosya türü: $mime";
            return false;
        }

        // Ensure directory exists
        $uploadPath = ROOT . DS . str_replace(['/', '\\'], DS, trim(static::$options['dir'], '/\\'));
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Generate filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = static::$options['encrypt_name'] ? bin2hex(random_bytes(10)) : pathinfo($file['name'], PATHINFO_FILENAME);

        $finalExtension = (static::$options['webp'] && strpos($mime, 'image/') === 0) ? 'webp' : $extension;
        $finalName = $filename . '.' . $finalExtension;
        $targetFile = $uploadPath . DS . $finalName;

        // Handle WebP conversion
        if (static::$options['webp'] && strpos($mime, 'image/') === 0) {
            if (static::convertAndSave($file['tmp_name'], $targetFile, $mime)) {
                return static::getResult($finalName, $file['size'], 'image/webp');
            }
            return false;
        }

        // Move standard file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return static::getResult($finalName, $file['size'], $mime);
        }

        static::$errors[] = 'Dosya taşınırken hata oluştu.';
        return false;
    }

    /**
     * Convert image to WebP and save
     */
    protected static function convertAndSave($source, $destination, $mime)
    {
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source);
                break;
            default:
                return false;
        }

        if (!$image) {
            static::$errors[] = 'Görsel işlenemedi.';
            return false;
        }

        $result = imagewebp($image, $destination, static::$options['quality']);
        imagedestroy($image);
        return $result;
    }

    /**
     * Format the result data
     */
    protected static function getResult($filename, $size, $mime)
    {
        $baseUrl = env('APP_URL');
        $pathPrefix = static::$options['dir'] ? static::$options['dir'] . '/' : '';

        return [
            'name' => $filename,
            'url' => $baseUrl . '/' . $pathPrefix . $filename,
            'path' => static::$options['dir'] . '/' . $filename,
            'mime' => $mime,
            'size' => $size
        ];
    }

    /**
     * Get errors
     */
    public static function errors()
    {
        return static::$errors;
    }
}
