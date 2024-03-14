<?php

namespace App\Service\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class ExchangeDTO
{
    protected string $effectiveDate;
    protected int $sourceId;
    protected ArrayCollection $rates;

    public function __construct()
    {
       $this->rates = new ArrayCollection();
    }

    public  function setDTO(string $effectiveDate, int $sourceId, array $rates)
    {
        $this->setEffectiveDate($effectiveDate);
        $this->setSourceId($sourceId);

        foreach ($rates as $rate) {
            $rateDTO = new RateDTO();
            $rateDTO->setCurrency($rate['currency']);
            $rateDTO->setCode($rate['code']);
            $rateDTO->setMid($rate['mid']);

            $this->addRate($rateDTO);
        }
    }

    public function addRate(RateDTO $rateDTO)
    {
//        $this->rates[] = $rateDTO;
        $this->rates->add($rateDTO);
    }

    /**
     * @return string
     */
    public function getEffectiveDate(): string
    {
        return $this->effectiveDate;
    }

    /**
     * @param string $effectiveDate
     */
    public function setEffectiveDate(string $effectiveDate): void
    {
        $this->effectiveDate = $effectiveDate;
    }

    /**
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * @param int $sourceId
     */
    public function setSourceId(int $sourceId): void
    {
        $this->sourceId = $sourceId;
    }

    /**
     * @return array
     */
    public function getRates(): array
    {
        return $this->rates;
    }

}
