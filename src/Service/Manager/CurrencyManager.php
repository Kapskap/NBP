<?php

namespace App\Service\Manager;

use App\Repository\CurrencyRepository;
use App\Entity\Currency;

class CurrencyManager
{
    public function __construct(private CurrencyRepository $currencyRepository)
    {
        $this->CurrencyRepository = $currencyRepository;
    }

    public function CheckAndAddCurrency(string $code, string $name, int $sourceId): int
    {
        $currency = $this->currencyRepository->getIdByCode($code);

        //Gdy kod waluty nie zostaje znaleziony dodanie go wraz z nazwą do tabeli currency
        if (!isset($currency[0])) {
            if ($sourceId == 1) {
                $namePl = $name;
                $nameEn = '';
            }
            elseif ($sourceId == 2){
                $namePl = '';
                $nameEn = $name;
            }
            $this->currencyRepository->insertCurrency($code, $namePl, $nameEn);
            $currency = $this->currencyRepository->getIdByCode($code);
        }
        $currencyId = $currency[0]->getId();
        return $currencyId;
    }

}