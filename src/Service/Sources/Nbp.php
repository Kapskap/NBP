<?php

namespace App\Service\Sources;

use App\Service\Interfaces\SourceInterface;

class Nbp implements SourceInterface
{
    public function getData(): array
	{
        $jsonContent = file_get_contents("https://api.nbp.pl/api/exchangerates/tables/A/?format=json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            foreach ($data as $array) {
                $effectiveDate = $array['effectiveDate'];
                $rates = $array['rates'];
            }

            $sourceId = 1;

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