<?php

namespace App\Service\Sources;

use App\Service\Interfaces\SourceInterface;

class CoinCap implements SourceInterface
{
    public function getData(): array
    {
        $divider = 100000000;
        $jsonContent = file_get_contents("https://api.coincap.io/v2/assets");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);
            $rates = [];
            $i = 0;

            foreach ($data['data'] as $rate) {
                $currency = $rate['name'];
                $code = $rate['symbol'];
                $mid = (int)round($rate['priceUsd']*$divider, 0);
                $rates[$i]['currency'] = $currency;
                $rates[$i]['code'] = $code;
                $rates[$i]['mid'] = $mid;
                $i++;

            }

            $effectiveDate = date("Y-m-d H:i:s", substr($data['timestamp'],0,10));
            $midCode = "USD";

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