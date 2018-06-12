<?php

namespace App\Service\Common;

use App\Request\Common\UploadImageRequest;
use App\Service\AbstractHandler;
use App\Domain\Common\Image;
use App\Domain\DomainRepository;

class UploadImageHandler extends AbstractHandler
{
    /**
     * @var DomainRepository
     */
    private $imageRepository;

    public function __construct(
        DomainRepository $imageRepository
    ) {
        $this->imageRepository = $imageRepository;
    }

    public function __invoke(UploadImageRequest $request): array
    {
        $this->imageRepository->add($image = new Image($request->file));

        return $image->toArray();
    }
}
