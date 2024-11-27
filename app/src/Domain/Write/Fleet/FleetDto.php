<?php

namespace App\Domain\Write\Fleet;

use App\Infra\Persistence\Doctrine\ORM\Entity\User;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class FleetDto implements \Stringable
{
    private Uuid $id;

    public function __construct(
        #[Assert\NotBlank]
        public User $user,
        public ?string $label,
    ) {
        $this->id = new UuidV4();

        if (null === $this->label) {
            $this->label = sprintf('label auto %s', $this->user->getUsername());
        }
    }

    public function __toString(): string
    {
        return sprintf(
            'Fleet($id=%s, $label=%s)',
            $this->id,
            $this->label
        );
    }

    public function getId(): Uuid
    {
        return $this->id;
    }
}
