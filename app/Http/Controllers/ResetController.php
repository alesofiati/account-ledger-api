<?php

namespace App\Http\Controllers;

use App\Services\AccountService;

class ResetController extends Controller
{
    public function __invoke(AccountService $service)
    {
        $service->reset();

        return response('', 200);
    }
}
