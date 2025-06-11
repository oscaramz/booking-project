<?php

namespace App\Core;

class Request
{
    public static function getJsonBody(): array
    {
        $input = file_get_contents("php://input");
        return json_decode($input, true) ?? [];
    }
}
