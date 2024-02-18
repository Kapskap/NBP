<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\ExchangeRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;
use App\Service\CurrencyService;

class GetDataService
{
    public function __construct(
        private ExchangeRepository $exchangeRepository,
        private  CurrencyService $currencyService,
        private  CurrencyRepository $currencyRepository
    )
    {
        $this->ExchangeRepository = $exchangeRepository;
        $this->CurrencyRepository = $this->currencyRepository;
        $this->currencyService = $currencyService;
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

                    //sprawdzenie czy podany kod waluty już istnieje, jeśli nie istnieje $currencyId = 0
                    $currencyId = $this->currencyService->getIdFromCode($code);

                    if($currencyId == 0){
                        $name = $rate['currency'];
                        $this->currencyRepository->insertCurrency($code, $name, '');
                        $currencyId = $this->currencyService->getIdFromCode($code);
                    }

                    $this->exchangeRepository->insertExchange($mid, $effectiveDate, $sourceId, $currencyId);
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

                    //sprawdzenie czy podany kod waluty już istnieje, jeśli nie istnieje $currencyId = 0
                    $currencyId = $this->currencyService->getIdFromCode($code);

                    if($currencyId == 0){
                        $name = $rate['name'];
                        $this->currencyRepository->insertCurrency($code, '', $name);
                        $currencyId = $this->currencyService->getIdFromCode($code);
                    }

                    $this->exchangeRepository->insertExchange($mid, $effectiveDate, $sourceId, $currencyId);
                }
                return 'Dane zostały pobrane poprawnie';
            }
        }
        else{
            return 'Nie udało się pobrać danych z serwera FloatRates';
        }

    }

}