<?php

namespace App\Domain;

use App\Domain\Common\Image;
use Doctrine\ORM\Mapping as ORM;

trait ImageRemoverEventTrait
{
    /**
     * @ORM\PreRemove()
     */
    public function removeImage()
    {
        if (!empty($this->image)) {
            $image = $this->image;
            /* @var Image $image */
            $image->setIsUsed(false);
        }

        if (isset($this->images)) {
            foreach ($this->images as $image) {
                if ($image) {
                    $image->setIsUsed(false);
                }
            }
        }
    }
}
