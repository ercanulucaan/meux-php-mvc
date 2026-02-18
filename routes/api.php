<?php

use Core\Router;
use App\Controllers\Api\FileLibraryController;

// File Library Routes
Router::get('/api/library', [FileLibraryController::class, 'index']);
Router::post('/api/upload', [FileLibraryController::class, 'upload']);
Router::post('/api/delete', [FileLibraryController::class, 'delete']);
Router::delete('/api/delete', [FileLibraryController::class, 'delete']);
