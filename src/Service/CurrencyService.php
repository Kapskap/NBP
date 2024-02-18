<?php

namespace App\Service;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Currency;


class CurrencyService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getIdFromCode(string $code): int
    {
        $em = $this->entityManager;
        $currencyRepository = $em->getRepository(Currency::class);

        $currency = $currencyRepository->findBy(['code' => $code]);
//        foreach ($currencies as $currency) {
//            $id = $currency->getId();
//        }

        if ($currency != NULL){
            $id = $currency[0]->getId();
            return $id;
        }
        else{
            return  0;
        }
    }
}