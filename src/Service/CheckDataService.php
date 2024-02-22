<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\ExchangeRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;
use phpDocumentor\Reflection\Types\Boolean;

class CheckDataService
{
    public function __construct(
        private ExchangeRepository $exchangeRepository,
        private CurrencyRepository $currencyRepository
    )
    {
        $this->ExchangeRepository = $exchangeRepository;
        $this->CurrencyRepository = $this->currencyRepository;
    }

    public function checkDate(string $effectiveDate, int $sourceId, array $rates): Boolean
    {
        $check = $this->exchangeRepository->findDate($effectiveDate);
//        dd($check);
        if ($check == NULL){
            return  true;
            foreach ($rates as $rate) {
                $code = $rate['code'];
                $mid = $rate['mid'];

                $currency = $this->currencyRepository->getIdByCode($code);
                dd($currency);
            }
        }
        else{
            return false;
        }
    }

}