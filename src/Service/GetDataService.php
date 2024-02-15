<?php

namespace App\Service;

use App\Entity\Language;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;
use App\Service\LanguageService;

class GetDataService
{
    public function __construct(private ExchangeRepository $exchangeRepository)
    {
        $this->ExchangeRepository = $exchangeRepository;
    }

    public function getDataFromNBP(): bool
    {
        $jsonContent = file_get_contents("https://api.nbp.pl/api/exchangerates/tables/A/?format=json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            foreach ($data as $array) {
                $effectiveDate = $array['effectiveDate'];
                $rates = $array['rates'];
            }

            $sourceId = 1;
            foreach ($rates as $rate) {
                $code = $rate['code'];
                $mid = $rate['mid'];

                $this->exchangeRepository->insertExchange($code, $mid, $effectiveDate, $sourceId);
            }
            return true;
        }
        else{
            return false;
        }
    }

    public function getDataFromFloatrates(): bool
    {
        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $sourceId = 2;
            foreach ($data as $rate) {
                $code = $rate['code'];
                $mid = $rate['inverseRate'];
                $effectiveDate = date("Y-m-d", strtotime($rate['date']));

                $this->exchangeRepository->insertExchange($code, $mid, $effectiveDate, $sourceId);
            }
            return true;
        }
        else{
            return false;
        }

    }

}