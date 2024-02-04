<?php

namespace App\Entity;


use App\Repository\ExchangeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    private ?string $currency = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $code = null;

    #[ORM\Column]
    private ?float $mid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $importAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getMid(): ?float
    {
        return $this->mid;
    }

    public function setMid(float $mid): static
    {
        $this->mid = $mid;

        return $this;
    }

    public function getImportAt(): ?\DateTimeInterface
    {
        return $this->importAt;
    }

    public function setImportAt(\DateTimeInterface $importAt): static
    {
        $this->importAt = $importAt;

        return $this;
    }
}
