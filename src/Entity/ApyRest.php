<?php

namespace App\Entity;

use App\Repository\ApyRestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApyRestRepository::class)]
class ApyRest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $edpoint;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEdpoint(): ?string
    {
        return $this->edpoint;
    }

    public function setEdpoint(string $edpoint): self
    {
        $this->edpoint = $edpoint;

        return $this;
    }
}
