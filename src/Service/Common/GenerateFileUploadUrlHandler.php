<?php

namespace App\Service\Common;

use App\Request\Common\GenerateFileUploadUrlRequest;
use App\Service\AbstractHandler;
use App\Service\S3Bucket;

class GenerateFileUploadUrlHandler extends AbstractHandler
{
    /**
     * @var S3Bucket
     */
    private $s3Bucket;

    /**
     * @var GenerateS3FileNameHandler
     */
    private $generateS3FileNameHandler;

    public function __construct(
        S3Bucket $s3Bucket,
        GenerateS3FileNameHandler $generateS3FileNameHandler
    ) {
        $this->s3Bucket = $s3Bucket;
        $this->generateS3FileNameHandler = $generateS3FileNameHandler;
    }

    public function __invoke(GenerateFileUploadUrlRequest $request): array
    {
        return $this->s3Bucket->generateUploadUrl(
            ($this->generateS3FileNameHandler)((string) $request->extension)
        );
    }
}
