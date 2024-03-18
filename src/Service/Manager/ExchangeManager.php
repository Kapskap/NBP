<?php

namespace App\Service\Manager;

use App\Repository\ExchangeRepository;
use App\Service\Manager\CurrencyManager;
use App\Service\Dto\ExchangeDTO;
use App\Service\Dto\RateDTO;

class ExchangeManager
{
    public function __construct(
        private ExchangeRepository $exchangeRepository,
        private CurrencyManager $currencyManager,
        private ExchangeDTO $exchangeDTO,
        private RateDTO $rateDTO
    )
    {
        $this->ExchangeRepository = $exchangeRepository;
        $this->currencyManager = $currencyManager;
    }

    public function checkAndAddData(object $dto): Bool
    {
        $effectiveDate = $this->exchangeDTO->geteffectiveDate();
        $check = $this->exchangeRepository->findDate($effectiveDate); //Sprawdzenie czy dane z podaną datą są już w bazie

        if ($check == NULL) {
            $sourceId = $this->exchangeDTO->getSourceId();

            $rates = $this->exchangeDTO->getRates();
            foreach ($rates as $rate) {
                $code = $rate->getCode();
                $mid = $rate->getMid();
                $name = $rate->getCurrency();

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

