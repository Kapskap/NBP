<?php

class FloatRates implements SourceInterface
{
    public function getData(): array
	{
        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $rates = [];
            foreach ($data as $rate) {
                $currency = $rate['name'];
                $code = $rate['code'];
                $mid = $rate['inverseRate'];
                $rates = $rates + $currency + $code + $mid;
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