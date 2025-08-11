<?php

namespace App\Services\Account;

use App\Repositories\AccountRepository;
use Exception;
use Illuminate\Support\Facades\DB;

readonly class CreateAccountsService
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {}

    /**
     * @throws Exception
     */
    public function execute(array $data)
    {
        $data['user_id'] = request()->user()->id;

        try {
            return DB::transaction(fn () => $this->accountRepository->createAccount($data));
        } catch (Exception $e) {
            throw new Exception('Error when creating account' , 500, $e);
        }
    }
}
