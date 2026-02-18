<?php

namespace App\Controllers\Api;

use Core\Controller;
use Core\Request;
use Core\Response;
use Core\DB;
use Core\Upload;

class FileLibraryController extends Controller
{
    /**
     * List files with pagination and search
     */
    public function index()
    {
        $search = Request::get('search');
        $query = DB::table('files')->orderBy('id', 'DESC');

        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $pagination = $query->paginate(12);

        return Response::json([
            'data' => $pagination['data'],
            'meta' => [
                'current_page' => $pagination['current_page'],
                'last_page' => $pagination['last_page'],
                'total' => $pagination['total']
            ]
        ]);
    }

    /**
     * Handle file upload (Single or Multiple)
     */
    public function upload()
    {
        Upload::options([
            'dir' => 'public/uploads',
            'webp' => true,
            'quality' => 85
        ]);

        $results = [];

        if (isset($_FILES['files']['name'])) {
            $uploadedFiles = Upload::multiple('files');
        } else {
            $uploaded = Upload::single('file');
            $uploadedFiles = $uploaded ? [$uploaded] : false;
        }

        if (!$uploadedFiles) {
            return Response::json([
                'success' => false,
                'message' => Upload::errors()[0] ?? 'Yükleme başarısız.'
            ], 400);
        }

        foreach ($uploadedFiles as $file) {
            // Save to database
            DB::table('files')->insert([
                'name' => $file['name'],
                'path' => $file['path'],
                'url' => $file['url'],
                'mime_type' => $file['mime'],
                'size' => $file['size'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $results[] = $file;
        }

        return Response::json([
            'success' => true,
            'data' => (isset($_FILES['files']['name'])) ? $results : $results[0]
        ]);
    }

    /**
     * Handle file deletion
     */
    public function delete()
    {
        $id = Request::post('id') ?? Request::input('id');

        if (!$id) {
            return Response::json(['success' => false, 'message' => 'Geçersiz ID.'], 400);
        }

        $file = DB::table('files')->where('id', $id)->first();

        if (!$file) {
            return Response::json(['success' => false, 'message' => 'Dosya bulunamadı.'], 404);
        }

        // Delete from storage
        $absolutePath = ROOT . DS . str_replace(['/', '\\'], DS, $file['path']);
        if (file_exists($absolutePath)) {
            unlink($absolutePath);
        }

        // Delete from database
        DB::table('files')->where('id', $id)->delete();

        return Response::json(['success' => true]);
    }
}
