<?php

namespace App\Service\Manager;

use App\Repository\ExchangeRepository;
use App\Service\Manager\CurrencyManager;

class ExchangeManager
{
    public function __construct(
        private ExchangeRepository $exchangeRepository,
        private CurrencyManager $currencyManager
    )
    {
        $this->ExchangeRepository = $exchangeRepository;
        $this->currencyManager = $currencyManager;
    }

    public function checkAndAddData(string $effectiveDate, int $sourceId, array $rates): Bool
    {
        $check = $this->exchangeRepository->findDate($effectiveDate);
        if ($check == NULL) {

            foreach ($rates as $rate) {
                $code = $rate['code'];
                $mid = $rate['mid'];
                $name = $rate['currency'];

                $currencyId = $this->currencyManager->CheckAndAddCurrency($code, $name, $sourceId);

                $this->exchangeRepository->insertExchange($mid, $effectiveDate, $sourceId, $currencyId);
            }
            return true;
        }
        else{
            return false;
        }
    }
}

