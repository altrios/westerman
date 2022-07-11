<?php

namespace App\Entity;

use App\Repository\XmlRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: XmlRepository::class)]
class Xml
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $xml;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getXml(): ?string
    {
        return $this->xml;
    }

    public function setXml(?string $xml): self
    {
        $this->xml = $xml;

        return $this;
    }
}
