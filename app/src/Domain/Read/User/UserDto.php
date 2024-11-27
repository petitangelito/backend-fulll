<?php

declare(strict_types=1);

namespace App\Domain\Read\User;

use Symfony\Component\Uid\Uuid;

/**
 * No validation needed for this Dto.
 */
final class UserDto implements \Stringable
{
    public function __construct(
        public Uuid $id,
        public string $username,
        public string $password,
        public string $email,
        public ?string $company,
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            'User($id=%s, $username=%s)',
            $this->id,
            $this->username
        );
    }
}
