<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRepository::class)]
class Currency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $code = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $namePL = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nameEN = null;

    #[ORM\OneToMany(targetEntity: Exchange::class, mappedBy: 'language')]
    private Collection $exchanges;

    public function __construct()
    {
        $this->exchanges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getNamePL(): ?string
    {
        return $this->namePL;
    }

    public function setNamePL(?string $namePL): static
    {
        $this->namePL = $namePL;

        return $this;
    }

    public function getNameEN(): ?string
    {
        return $this->nameEN;
    }

    public function setNameEN(?string $nameEN): static
    {
        $this->nameEN = $nameEN;

        return $this;
    }

    /**
     * @return Collection<int, Exchange>
     */
    public function getExchanges(): Collection
    {
        return $this->exchanges;
    }

    public function addExchange(Exchange $exchange): static
    {
        if (!$this->exchanges->contains($exchange)) {
            $this->exchanges->add($exchange);
            $exchange->setLanguage($this);
        }

        return $this;
    }

    public function removeExchange(Exchange $exchange): static
    {
        if ($this->exchanges->removeElement($exchange)) {
            // set the owning side to null (unless already changed)
            if ($exchange->getLanguage() === $this) {
                $exchange->setLanguage(null);
            }
        }

        return $this;
    }
}
