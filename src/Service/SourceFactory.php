<?php

namespace App\Service;

use App\Service\Interfaces\SourceFactoryInterface;
use App\Service\Interfaces\SourceInterface;
use App\Service\Sources\Nbp;
use App\Service\Sources\FloatRates;
use App\Service\CheckDataService;

class SourceFactory implements SourceFactoryInterface
{
    public function __construct(private  CheckDataService $checkDataService)
    {
        $this->checkDataService = $checkDataService;
    }
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

        $effectiveDate = $rates['effectiveDate'];
        $sourceId = $rates['sourceId'];
//        dd($rates, $effectiveDate, $sourceId);

        $test = $this->checkDataService->checkDate($effectiveDate, $sourceId, $rates);
dd($test);

    }
