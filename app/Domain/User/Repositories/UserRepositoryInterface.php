<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\UserEntity;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity;

    public function create(array $data): UserEntity;
}
