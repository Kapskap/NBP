<?php

namespace App\Service\Sources;

use App\Service\Interfaces\SourceInterface;

class FloatRates implements SourceInterface
{
    public function getData(): array
	{
        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $rates = [];
            $i = 0;
            foreach ($data as $rate) {
                $currency = $rate['name'];
                $code = $rate['code'];
                $mid = $rate['inverseRate'];
                $rates[$i]['currency'] = $currency;
                $rates[$i]['code'] = $code;
                $rates[$i]['mid'] = $mid;
                $i++;
            }

            $effectiveDate = date("Y-m-d", strtotime($data['usd']['date']));
            $sourceId = 2;
            return [
                'effectiveDate' => $effectiveDate,
                'sourceId' => $sourceId,
                'rates' => $rates,
            ];
        }
        else{
            return [];
        }
	}
}