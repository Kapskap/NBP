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
            elseif ($sourceId == 3){
                $namePl = '';
                $nameEn = $name;
            }
            $currencyId = $this->currencyRepository->insertCurrency($code, $namePl, $nameEn);
        }
        else { //sprawdzenie i ewntualne dodanie nazwy waluty do tabeli currency
            $currencyId = $currency[0]->getId();
            $namePl = $currency[0]->getNamePL();
            $nameEn = $currency[0]->getNameEN();

            if (($sourceId == 1) && ($namePl != $name)){
                $namePl = $name;
                $this->currencyRepository->updateCurrency($code, $namePl, $nameEn);
            }
            if (($sourceId == 2) && ($nameEn != $name)){
                $nameEn = $name;
                $this->currencyRepository->updateCurrency($code, $namePl, $nameEn);
            }
            if (($sourceId == 3) && ($nameEn != $name)){
                $nameEn = $name;
                $this->currencyRepository->updateCurrency($code, $namePl, $nameEn);
            }
        }

        return $currencyId;
    }

}