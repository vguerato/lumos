<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new User());
    }

    public function createUser(array $data): Model
    {
        return $this->query()->create($data);
    }

    public function getUserByEmail($email): object|null
    {
        return $this->filter(['email' => $email])->first();
    }
}
