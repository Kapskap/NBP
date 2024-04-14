<?php

namespace App\Service\Sources;

use App\Service\Interfaces\SourceInterface;

class Nbp implements SourceInterface
{
    public function getData(): array
	{
        $divider = 100000000;
        $jsonContent = file_get_contents("https://api.nbp.pl/api/exchangerates/tables/A/?format=json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            foreach ($data as $array) {
                $effectiveDate = $array['effectiveDate'];
                $data = $array['rates'];
            }

            $midCode = "PLN";

            $rates = [];
            $i = 0;
            foreach ($data as $rate) {
                $currency = $rate['currency'];
                $code = $rate['code'];
                $mid = (int)round($rate['mid']*$divider, 0);
                $rates[$i]['currency'] = $currency;
                $rates[$i]['code'] = $code;
                $rates[$i]['mid'] = $mid;
                $i++;
            }

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