<?php

namespace App\Service\Dto;

use Money\Money;

class RateDTO
{
    protected string $currency;
    protected string $code;
    protected Money $mid;

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
    public function getMid(): Money
    {
        return $this->mid;
    }

    /**
     * @param float $mid
     */
    public function setMid(Money $mid): void
    {
        $this->mid = $mid;
    }

}