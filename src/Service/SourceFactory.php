<?php

namespace App\Service;

use App\Service\Interfaces\SourceFactoryInterface;
use App\Service\Interfaces\SourceInterface;

class SourceFactory implements SourceFactoryInterface
{
    public static function createObject(string $source): SourceInterface
    {
        if ($source === 'NBP') {
            return new Nbp();
        }

        if ($source === 'FloatRates') {
            return new FloatRates();
        }

        throw new \InvalidArgumentException("Unsupported Source");
    }
}
    $sources = ['NBP', 'FloatRates'];

    foreach ($sources as $source) {
        $source = SourceFactory::createObject($source);    //SourceInterface
        $rates = $source->getData();
        //coś robimy z tym $rates
    }