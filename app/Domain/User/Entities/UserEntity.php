<?php

namespace App\Domain\User\Entities;

final readonly class UserEntity
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
    ) {}
}
