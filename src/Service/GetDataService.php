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

    public function getDataFromNBP(): string
    {
        $jsonContent = file_get_contents("https://api.nbp.pl/api/exchangerates/tables/A/?format=json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            foreach ($data as $array) {
                $effectiveDate = $array['effectiveDate'];
                $rates = $array['rates'];
            }

            $sourceId = 1;

            $check = $this->exchangeRepository->findDate($effectiveDate);
            if ($check != NULL) {
                return 'Dane z podaną datą już istnieją';
            }
            else {

                foreach ($rates as $rate) {
                    $code = $rate['code'];
                    $mid = $rate['mid'];

                    $this->exchangeRepository->insertExchange($code, $mid, $effectiveDate, $sourceId);
                }
                return 'Dane zostały pobrane poprawnie';
            }
        }
        else{
            return 'Nie udało się pobrać danych z serwera NBP';
        }
    }

    public function getDataFromFloatrates(): string
    {
        $jsonContent = file_get_contents("https://www.floatrates.com/daily/pln.json");

        if ($jsonContent != NULL) {
            $data = json_decode($jsonContent, true);

            $sourceId = 2;
            $effectiveDate = date("Y-m-d", strtotime($data['usd']['date']));

            $check = $this->exchangeRepository->findDate($effectiveDate);
            if ($check != NULL) {
                return 'Dane z podaną datą już istnieją';
            }
            else {
                foreach ($data as $rate) {
                    $code = $rate['code'];
                    $mid = $rate['inverseRate'];
                    $effectiveDate = date("Y-m-d", strtotime($rate['date']));

                    $this->exchangeRepository->insertExchange($code, $mid, $effectiveDate, $sourceId);
                }
                return 'Dane zostały pobrane poprawnie';
            }
        }
        else{
            return 'Nie udało się pobrać danych z serwera FloatRates';
        }

    }

}