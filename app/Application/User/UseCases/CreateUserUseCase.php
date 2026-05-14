<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\CreateUserData;
use App\Domain\User\Entities\UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;

final readonly class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users,
    ) {}

    public function execute(CreateUserData $data): UserEntity
    {
        return $this->users->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);
    }
}
