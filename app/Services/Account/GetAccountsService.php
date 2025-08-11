<?php

namespace App\Services\Account;

use App\Repositories\AccountRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

readonly class GetAccountsService
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {}

    public function execute(): Collection
    {
        Cache::put(random_bytes(10), json_encode(request()->user()));

        $userId = request()->user()->id;
        return $this->accountRepository->getAccountsByUserId($userId);
    }
}
