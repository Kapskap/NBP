<?php

namespace App\Service\Sources;

use App\Service\Interfaces\SourceInterface;

class FloatRates implements SourceInterface
{
    public function getData(): array
	{
        $divider = 100000000;
        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $rates = [];
            $i = 0;
            foreach ($data as $rate) {
                $currency = $rate['name'];
                $code = $rate['code'];
                $mid = (int)round($rate['inverseRate']*$divider, 0);
                $rates[$i]['currency'] = $currency;
                $rates[$i]['code'] = $code;
                $rates[$i]['mid'] = $mid;
                $i++;
            }

            $effectiveDate = date("Y-m-d", strtotime($data['usd']['date']));
            $midCode = "PLN";

            return [
                'effectiveDate' => $effectiveDate,
                'rates' => $rates,
                'midCode' => $midCode,
            ];
        }
        else{
            return [];
        }
	}
}