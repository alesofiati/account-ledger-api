<?php

namespace App\Http\Controllers;

use App\Services\AccountService;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function __invoke(Request $request, AccountService $service)
    {
        $balance = $service->findBalance(
            $request->query('account_id')
        );

        if ($balance === null) {
            return response()->json('0', 404);
        }

        return response((string) $balance, 200);
    }
}
