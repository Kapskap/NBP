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

    public function AddData(object $dto): Bool
    {
        $effectiveDate = $dto->geteffectiveDate();
        $sourceId = $dto->getSourceId();
        //Sprawdzenie czy dane z podaną datą oraz źródłem danych są już w bazie
        $check = $this->exchangeRepository->findDateAndSourceId($effectiveDate, $sourceId);

        if ($check == NULL) {
            $rates = $dto->getRates();
            foreach ($rates as $rate) {
                $code = $rate->getCode();
                $mid = $rate->getMid()->getAmount();
                $midCode = $rate->getMid()->getCurrency()->getCode();
                $name = $rate->getCurrency();

                $currencyId = $this->currencyManager->CheckAndAddCurrency($code, $name, $sourceId);

                $this->exchangeRepository->insertExchange($mid, $midCode, $effectiveDate, $sourceId, $currencyId);
            }
            return true;
        }
        else{
            return false;
        }
    }
}

