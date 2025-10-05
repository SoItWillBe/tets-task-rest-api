<?php

namespace App\Controllers;

class Welcome extends Controller
{

    public function index()
    {
        $this->jsonResponse(['message' => 'Hello World!']);
    }

}
