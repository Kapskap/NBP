<?php

namespace App\Service;

use App\Service\Interfaces\SourceFactoryInterface;
use App\Service\Interfaces\SourceInterface;
use App\Service\Sources\Nbp;
use App\Service\Sources\FloatRates;
use App\Service\Sources\CoinCap;


class SourceFactory implements SourceFactoryInterface
{
    public function createObject(string $source): SourceInterface
    {
        if ($source === 'Narodowy Bank Polski') {
            return new Nbp();
        }

        if ($source === 'Float Rates') {
            return new FloatRates();
        }

        if ($source === 'Coin Cap') {
            return new CoinCap();
        }

        throw new \InvalidArgumentException("Unsupported Source");
    }
}

