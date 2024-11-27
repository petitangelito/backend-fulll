<?php

declare(strict_types=1);

namespace App\Domain\Write\Ressource;

use App\Domain\Shared\Validator\ModeRessource;
use App\Infra\Persistence\Doctrine\ORM\Entity\Fleet;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class RessourceDto implements \Stringable
{
    public const MODE_TRAIN = 'train';
    public const MODE_TRUCK = 'truck';
    public const MODE_VESSEL = 'vessel';
    public const MODE_CONTAINER = 'container';
    public const MODE_PLANE = 'plane';
    public const MODE_TRAILER = 'trailer';
    public const MODE_MOBILE = 'mobile';

    public const MODES = [
        self::MODE_TRAIN,
        self::MODE_TRUCK,
        self::MODE_VESSEL,
        self::MODE_CONTAINER,
        self::MODE_PLANE,
        self::MODE_TRAILER,
        self::MODE_MOBILE,
    ];
    private Uuid $id;

    public function __construct(
        #[Assert\NotBlank]
        public Fleet $fleet,
        #[Assert\Type('string')]
        #[Assert\NotBlank]
        #[Assert\Length(min: 1, max: 40)]
        public string $plate_number,
        #[ModeRessource]
        public ?string $mode,
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
            'Ressource($id=%s, $plate_number=%s)',
            $this->id,
            $this->plate_number
        );
    }
}
