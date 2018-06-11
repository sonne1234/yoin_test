<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Common\S3UploadedFileName;

class DoctrineS3UploadedFileNameRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return S3UploadedFileName::class;
    }
}
