<?php

namespace App\Domain\Common;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class S3UploadedFileName
{
    const TABLE_NAME = 's3uploadedfilename';

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $fileName;

    public function __construct(string $filename)
    {
        $this->fileName = $filename;
    }
}
