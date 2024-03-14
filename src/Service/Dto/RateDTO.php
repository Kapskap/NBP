<?php

namespace App\Service\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class RateDTO
{
    protected string $currency;
    protected string $code;
    protected float $mid;

//    public function set(string $currency, string $code, float $mid): arraycollection
//    {
//        $rates[$i]['currency'] = $currency;
//        $rates[$i]['code'] = $code;
//        $rates[$i]['mid'] = $mid;
//        return $rates;
//    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return float
     */
    public function getMid(): float
    {
        return $this->mid;
    }

    /**
     * @param float $mid
     */
    public function setMid(float $mid): void
    {
        $this->mid = $mid;
    }


}