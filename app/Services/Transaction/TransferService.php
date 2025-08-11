<?php

namespace App\Services\Transaction;

use App\Enums\TransactionType;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Services\Transaction\Concerns\Math;
use Exception;
use Illuminate\Support\Facades\DB;

readonly class TransferService
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
        $accountAddressFrom = $data['account_from'];
        $accountAddressTo = $data['account_to'];
        $amount = $data['amount'];
        $description = $data['description'] ?? null;

        $accountFrom = $this->accountRepository->getAccountById($accountAddressFrom);
        if (!$accountFrom) {
            throw new Exception('Sender account not found.', 404);
        }

        $accountTo = $this->accountRepository->getAccountById($accountAddressTo);
        if (!$accountTo) {
            throw new Exception('Receiver account not found.', 404);
        }

        if ($this->shouldNegative($accountFrom->balance, $amount)) {
            throw new Exception('Insufficient funds.', 400);
        }

        try {
            DB::beginTransaction();

            $accountFrom->balance = $this->subtract($accountFrom->balance, $amount);
            $accountTo->balance = $this->sum($accountTo->balance, $amount);

            $accountFrom->save();
            $accountTo->save();

            $transaction = $this->transactionRepository->createTransaction([
                'account_id' => $accountAddressTo,
                'type' => TransactionType::WITHDRAWAL,
                'amount' => $amount,
                'description' => $description,
            ]);

            $this->transactionRepository->createTransaction([
                'account_id' => $accountAddressFrom,
                'type' => TransactionType::DEPOSIT,
                'amount' => $amount,
                'description' => $description,
            ]);

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Cannot complete transaction.', 500, $e);
        } finally {
            DB::commit();
        }
    }
}
