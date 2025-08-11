<?php

namespace App\Services\Account;

use App\Repositories\AccountRepository;
use RuntimeException;

readonly class DeleteAccountsService
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {}

    public function execute(string $accountId): bool
    {
        $userId = request()->user()->id;

        $accountRemoved = $this->accountRepository->deleteAccountByUser($accountId, $userId);
        if (!$accountRemoved) {
            throw new RuntimeException('Cannot delete account.', 500);
        }

        return true;
    }
}
