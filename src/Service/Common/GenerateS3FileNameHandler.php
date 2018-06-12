<?php

namespace App\Service\Common;

use App\Domain\Common\Criteria\S3UploadedFileByFileNameCriteria;
use App\Domain\Common\S3UploadedFileName;
use App\Domain\DomainRepository;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\Uuid;

class GenerateS3FileNameHandler
{
    /**
     * @var DomainRepository
     */
    private $s3UploadedFileNamesRepository;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(
        DomainRepository $s3UploadedFileNamesRepository,
        EntityManager $entityManager
    ) {
        $this->s3UploadedFileNamesRepository = $s3UploadedFileNamesRepository;
        $this->em = $entityManager;
    }

    public function __invoke(string $extension): string
    {
        $fileName = '';

        $this->em->transactional(function ($em) use ($extension, &$fileName) {
            /* @var EntityManager $em */
            $em
                ->getConnection()
                ->exec('LOCK TABLE ONLY "'.S3UploadedFileName::TABLE_NAME.'" IN ACCESS EXCLUSIVE MODE;');

            while (true) {
                $fileName = Uuid::uuid4().($extension === '' ? '' : ".$extension");
                if (!$this->s3UploadedFileNamesRepository->getOneByCriteria(
                    new S3UploadedFileByFileNameCriteria($fileName)
                )) {
                    break;
                }
            }

            $this->s3UploadedFileNamesRepository->add(
                new S3UploadedFileName($fileName)
            );
        });

        return $fileName;
    }
}
