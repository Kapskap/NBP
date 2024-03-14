<?php

namespace App\Service\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class RateDTO
{
    public function ReturnArrayCollection(int $i, string $currency, string $code, float $mid): arraycollection
    {
//        return new ArrayCollection($rates);
        $rates[$i]['currency'] = $currency;
        $rates[$i]['code'] = $code;
        $rates[$i]['mid'] = $mid;
        return $rates;
    }
}