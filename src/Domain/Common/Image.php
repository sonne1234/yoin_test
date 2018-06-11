<?php

namespace App\Domain\Common;

use App\Domain\Common\Event\ImageCreatedEvent;
use App\Domain\DomainEventPublisher;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Image
{
    const MAX_IMAGE_SIZE = 5;
    const MAX_IMAGE_WIDTH = 3000;
    const MAX_IMAGE_HEIGHT = 3000;
    const CROPPED_WIDTH = 300;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @Vich\UploadableField(mapping="cropped_images", fileNameProperty="imageNameCropped")
     *
     * @var File
     */
    private $imageCroppedFile;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     *
     * @var string
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", unique=true, nullable=true)
     *
     * @var string
     */
    private $imageNameCropped;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $isUsed;

    /**
     * @var string
     */
    public $uniqueS3FileName;

    public function __construct(File $file, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->imageFile = $file;
        $this->updatedAt = new \DateTime();
        $this->isUsed = false;

        DomainEventPublisher::instance()->publish(new ImageCreatedEvent($this->id));
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $file): self
    {
        $this->imageFile = $file;

        return $this;
    }

    public function getImageCroppedFile(): ?File
    {
        return $this->imageCroppedFile;
    }

    public function setImageCroppedFile(File $file): self
    {
        $this->imageCroppedFile = $file;
        $this->updatedAt = new \DateTime();

        return $this;
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function setImageName(?string $name): self
    {
        $this->imageName = $name;

        return $this;
    }

    public function getImageNameCropped(): ?string
    {
        return $this->imageNameCropped;
    }

    public function setImageNameCropped(?string $name): self
    {
        $this->imageNameCropped = $name;

        return $this;
    }

    public function setIsUsed(bool $used): self
    {
        $this->isUsed = $used;

        return $this;
    }

    public function getIsUsed(): bool
    {
        return $this->isUsed;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'imageUrl' => getenv('AWS_IMAGES_PREFIX').'/'.$this->imageName,
            'croppedImageUrl' => getenv('AWS_IMAGES_PREFIX').'/'.($this->imageNameCropped ?? $this->imageName),
        ];
    }
}
