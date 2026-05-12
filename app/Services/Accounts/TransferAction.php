<?php

namespace App\Services\Accounts;

use App\Contracts\AccountRepository;

readonly class TransferAction
{
    public function __construct(private AccountRepository $accounts)
    {
    }

    public function __invoke(string $origin, string $destination, int $amount): ?array
    {
        $originBalance = $this->accounts->get($origin);

        if ($originBalance === null) {
            return null;
        }

        $destinationBalance = $this->accounts->get($destination) ?? 0;
        $newOriginBalance = $originBalance - $amount;
        $newDestinationBalance = $destinationBalance + $amount;

        $this->accounts->set($origin, $newOriginBalance);
        $this->accounts->set($destination, $newDestinationBalance);

        return [
            'origin' => [
                'id' => $origin,
                'balance' => $newOriginBalance,
            ],
            'destination' => [
                'id' => $destination,
                'balance' => $newDestinationBalance,
            ],
        ];
    }
}

