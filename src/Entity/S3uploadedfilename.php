<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * S3uploadedfilename
 *
 * @ORM\Table(name="s3uploadedfilename", uniqueConstraints={@ORM\UniqueConstraint(name="uniq_2dfa31c09c39465b", columns={"filename"})})
 * @ORM\Entity
 */
class S3uploadedfilename
{
    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="s3uploadedfilename_filename_seq", allocationSize=1, initialValue=1)
     */
    private $filename;

    public function getFilename(): ?string
    {
        return $this->filename;
    }


}
