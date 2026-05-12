<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Services\AccountService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __invoke(Request $request, AccountService $accountService)
    {
        $type = $request->input('type');

        $response = match ($type) {
            TransactionType::Deposit->value => $accountService->deposit(
                $request->input('destination'),
                $request->input('amount')
            ),

            TransactionType::Withdraw->value => $accountService->withdraw(
                $request->input('origin'),
                $request->input('amount')
            ),

            TransactionType::Transfer->value => $accountService->transfer(
                $request->input('origin'),
                $request->input('destination'),
                $request->input('amount')
            ),

            default => null
        };

        if (is_null($response)) {
            return response('0', 404);
        }

        return response()->json($response, 201);
    }
}
