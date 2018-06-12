<?php

namespace App\Service;

use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;

class S3Bucket
{
    const MAX_UPLOAD_TIME_LIMIT = 30; //minutes

    /**
     * @var string
     */
    private $s3BucketName;

    /**
     * @var S3ClientInterface
     */
    private $s3Client;

    public function __construct(S3Client $s3Client)
    {
        $this->s3Client = $s3Client;
    }

    public function setS3BucketName(string $s3BucketName)
    {
        $this->s3BucketName = $s3BucketName;
    }

    public function generateUploadUrl(string $fileName): array
    {
        return [
            'uploadUrl' => $uploadUrl = (string) $this
                    ->s3Client
                    ->createPresignedRequest(
                        $this->s3Client->getCommand(
                            'PutObject',
                            [
                                'Bucket' => $this->s3BucketName,
                                'Key' => $fileName,
                                'ACL' => 'public-read',
                            ]
                        ),
                        new \DateTime('+'.self::MAX_UPLOAD_TIME_LIMIT.' minutes')
                    )
                    ->getUri(),
            'downloadUrl' => strstr($uploadUrl, '?', true),
        ];
    }
}
