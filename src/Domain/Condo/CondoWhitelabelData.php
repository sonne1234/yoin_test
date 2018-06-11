<?php

namespace App\Domain\Condo;

use App\Domain\ToArrayTransformTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class CondoWhitelabelData
{
    use ToArrayTransformTrait;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $colorScheme;

    public function __construct(
        string $colorScheme
    ) {
        $this->colorScheme = $colorScheme;
    }
}
