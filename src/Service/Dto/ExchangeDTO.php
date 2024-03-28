<?php

namespace App\Service\Dto;

use Doctrine\Common\Collections\ArrayCollection;
use Money\Currency;
use Money\Money;

class ExchangeDTO
{
    protected string $effectiveDate;
    protected int $sourceId;
    protected ArrayCollection $rates;

    public function __construct()
    {
       $this->rates = new ArrayCollection();
    }

    public  function setDTO(string $effectiveDate, int $sourceId, array $rates,string $money = "PLN")
    {
        $this->setEffectiveDate($effectiveDate);
        $this->setSourceId($sourceId);

        foreach ($rates as $rate) {
            $rateDTO = new RateDTO();
            $rateDTO->setCurrency($rate['currency']);
            $rateDTO->setCode($rate['code']);
            $rateDTO->setMid(Money::$money($rate['mid']));
//            $rateDTO->setMid($rate['mid']);

            $this->addRate($rateDTO);
        }
    }

    public function addRate(RateDTO $rateDTO)
    {
//        $this->rates[] = $rateDTO; //array
        $this->rates->add($rateDTO); //ArrayCollection
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
        return $this->rates->getValues();
    }

}
