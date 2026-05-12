<?php

namespace App\Http\Controllers;

use App\Storage\MemoryStorage;

class ResetController extends Controller
{
    public function __invoke()
    {
        MemoryStorage::$accounts = [];

        return response('', 200);
    }
}
