<?php

declare(strict_types=1);

namespace App\Domain\Write\User;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class UserDto implements \Stringable
{
    private Uuid $id;

    public function __construct(
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 180)]
        public string $username,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 180)]
        public string $password,
        #[Assert\Type('string')]
        #[Assert\Email]
        #[Assert\Length(min: 1, max: 255)]
        public string $email,
        public ?string $company,
    ) {
        $this->id = new UuidV4();
    }

    public function getId(): Uuid
    {
        return $this->id;
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
