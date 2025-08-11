<?php

namespace App\Repositories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Uid\Ulid;

class AccountRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Account());
    }

    public function createAccount(array $data): ?Model
    {
        $data['id'] = Ulid::generate();
        return $this->query()->create($data);
    }

    public function getAccountById(string $accountId): object
    {
        return $this->query()->where('id', $accountId)->first();
    }

    public function getAccountsByUserId(int $userId): Collection
    {
        return $this->filter(['user_id' => $userId])->get();
    }

    public function deleteAccountByUser(string $accountId, int $userId): bool
    {
        return $this->query()
            ->where('id', $accountId)
            ->where('user_id', $userId)
            ->delete();
    }
}
