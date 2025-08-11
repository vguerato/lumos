<?php

namespace App\Services\Account;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Exception;

readonly class GetAccountDetailService
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {}

    /**
     * @param string $accountId
     * @param bool $complete
     * @return Account
     */
    public function execute(string $accountId, bool $complete = false): Account
    {
        /** @var Account $account */
        $account = $this->accountRepository->getAccountById($accountId);

        if ($complete) {
            $account->load('transactions');
        }

        return $account;
    }
}
