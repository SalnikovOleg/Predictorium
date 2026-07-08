<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function notFoundReponse()
    {
        return response()->json(['status' => 'false', 'message' => 'Page not found'], 404);
    }
}
