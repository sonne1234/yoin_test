<?php

namespace App\Service;

use App\Domain\Common\Exception\ImageNotFoundException;
use App\Domain\Common\Image;
use App\Domain\DomainRepository;

trait ImageFinderTrait
{
    /**
     * @var DomainRepository
     */
    private $imageRepository;

    private function useImage(string $id): ?Image
    {
        if ($id === '') {
            return null;
        }

        /** @var Image $image */
        if (!$image = $this->imageRepository->get($id)) {
            throw new ImageNotFoundException();
        }

        return $image->setIsUsed(true);
    }

    private function replaceImage(string $id, ?Image $currentImage): ?Image
    {
        if ($currentImage) {
            $currentImage->setIsUsed(false);
        }

        return $this->useImage($id);
    }

    /**
     * @param iterable|Image[] $currentImages
     * @param array $imagesIds
     * @return array|Image[]
     */
    private function replaceImages(iterable $currentImages, array $imagesIds): array
    {
        foreach ($currentImages as $image) {
            $image->setIsUsed(false);
        }

        $res = [];

        foreach ($imagesIds as $id) {
            if ($image = $this->useImage($id)) {
                $res[] = $image;
            }
        }

        return $res;
    }
}
