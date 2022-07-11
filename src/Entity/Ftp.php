<?php

namespace App\Entity;

use App\Repository\FtpRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FtpRepository::class)]
class Ftp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $excel;

    #[ORM\Column(type: 'integer', length: 255)]
    private $hoja;

    #[ORM\Column(type: 'string', length: 255)]
    private $coordenadas;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExcel(): ?string
    {
        return $this->excel;
    }

    public function setExcel(string $excel): self
    {
        $this->excel = $excel;

        return $this;
    }
    public function getHoja(): ?string
    {
        return $this->hoja;
    }

    public function setHoja(string $hoja): self
    {
        $this->hojas = $hoja;

        return $this;
    }

    public function getCoordenadas(): ?string
    {
        return $this->coordenadas;
    }

    public function setCoordenadas(string $coordenadas): self
    {
        $this->coordenadas = $coordenadas;

        return $this;
    }
}
