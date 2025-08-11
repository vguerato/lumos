<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Transaction());
    }

    public function createTransaction(array $data): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = $this->query()->create($data);
        return $transaction;
    }
}
