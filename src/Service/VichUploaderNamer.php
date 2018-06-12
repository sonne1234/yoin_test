<?php

namespace App\Service;

use App\Service\Common\GenerateS3FileNameHandler;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class VichUploaderNamer implements \Vich\UploaderBundle\Naming\NamerInterface
{
    /**
     * @var GenerateS3FileNameHandler
     */
    private $generateS3FileNameHandler;

    public function __construct(
        GenerateS3FileNameHandler $generateS3FileNameHandler
    ) {
        $this->generateS3FileNameHandler = $generateS3FileNameHandler;
    }

    public function name($object, PropertyMapping $mapping): string
    {
        return ($this->generateS3FileNameHandler)('jpg');
    }
}
