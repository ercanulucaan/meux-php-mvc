<?php

use Core\Router;
use Core\Response;

Router::get('/api/users', function() {
    Response::json([
        ['id' => 1, 'name' => 'John Doe'],
        ['id' => 2, 'name' => 'Jane Doe']
    ]);
});
