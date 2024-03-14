<?php

namespace App\Service\Dto;

use App\Service\Dto\RateDTO;
use Doctrine\Common\Collections\ArrayCollection;
use function Clue\StreamFilter\append;


class ExchangeDTO
{
    protected string $effectiveDate;
    protected int $sourceId;
    protected array $rates;
    protected arraycollection $rateDTO;

//    public function __construct(private RateDTO $rateDTO)
//    {
//        $this->rateDTO = $rateDTO;
//    }

    public  function getDTO(string $effectiveDate, int $sourceId, array $rates)
    {
        $this->setEffectiveDate($effectiveDate);
        $this->setSourceId($sourceId);
//        $this->setRates($rates);

        foreach ($rates as $i => $rate) {

            $rateDTO = new RateDTO($i, $rate['currency'], $rate['code'], $rate['mid']);
            dd($rateDTO);
            $this->addRate($rateDTO);
        }
    }

    public function addRate(RateDTO $rateDTO)
    {
        $this->rates[] = $rateDTO;
        //ArrayCollection
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

    /**
     * @param array $rates
     */
    public function setRates(array $rates): void
    {
        $this->rates = new ArrayCollection($rates);
    }

}
