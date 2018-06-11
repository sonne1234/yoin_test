<?php

namespace App\Domain\Common\Criteria;

use App\Domain\DomainCriteria;
use Doctrine\Common\Collections\Criteria;

class S3UploadedFileByFileNameCriteria implements DomainCriteria
{
    /**
     * @var string
     */
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function create(): Criteria
    {
        return Criteria::create()->where(
            Criteria::expr()->eq('fileName', $this->fileName)
        );
    }
}
