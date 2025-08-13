<?php

namespace App\Services\Transaction;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\Transaction\Concerns\Math;
use Exception;
use Illuminate\Support\Facades\DB;

readonly class TransactService
{
    use Math;

    public function __construct(
        private TransactionRepository $transactionRepository,
        private AccountRepository $accountRepository,
    )
    {}

    /**
     * @throws Exception
     */
    public function execute(array $data): Transaction
    {
        $accountId = $data['account_id'];
        $account = $this->accountRepository->getAccountById($accountId);
        if (!$account) {
            throw new Exception('Account not found.', 404);
        }

        $amount = $data['amount'];
        $type = $data['type'];

        try {
            DB::beginTransaction();

            if ($type === TransactionType::WITHDRAWAL->value) {
                $this->hasBalance($account->balance, $amount);
                $account->balance = $this->subtract($account->balance, $amount);
            }
            else if ($type === TransactionType::DEPOSIT->value) {
                $account->balance = $this->sum($account->balance, $amount);
            }
            else {
                throw new Exception('Invalid transaction type.', 400);
            }

            $account->save();
            return $this->transactionRepository->createTransaction($data);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        } finally {
            DB::commit();
        }
    }

    /**
     * @param float $balance
     * @param float $value
     * @return void
     * @throws Exception
     */
    private function hasBalance(float $balance, float $value): void
    {
        if ($this->shouldNegative($balance, $value)) {
            throw new Exception('Insufficient funds', 400);
        }

    }
}
