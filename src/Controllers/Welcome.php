<?php

namespace App\Controllers;

use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;


class Welcome extends Controller
{

    public function index()
    {
        $this->jsonResponse(
            ResponseMessage::response(
                ResponseStatusesEnums::Success,
                'Hello World!'
            )
        );
    }

}
