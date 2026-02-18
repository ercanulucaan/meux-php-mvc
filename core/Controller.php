<?php

namespace Core;

class Controller
{
    public function view($path, $data = [])
    {
        View::render($path, $data);
    }

    public function json($data, $status = 200)
    {
        Response::json($data, $status);
    }

    public function request()
    {
        return new Request();
    }
}
