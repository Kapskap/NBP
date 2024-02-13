<?php

namespace App\Entity;


use App\Repository\ExchangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRepository::class)]
class Exchange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $code = null;

    #[ORM\Column]
    private ?float $mid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $importAt = null;

    #[ORM\ManyToOne(inversedBy: 'exchanges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Source $source = null;

    #[ORM\ManyToOne(inversedBy: 'exchanges')]
    private ?Language $language = null;


    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSource(): ?source
    {
        return $this->source;
    }

    public function setSource(?source $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getLanguage(): ?language
    {
        return $this->language;
    }

    public function setLanguage(?language $language): static
    {
        $this->language = $language;

        return $this;
    }

}
