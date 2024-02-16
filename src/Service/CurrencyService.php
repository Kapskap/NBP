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

    public function checkCode(string $code): int
    {
        $em = $this->entityManager;
        $currencyRepository = $em->getRepository(Currency::class);

        $currencyies = $currencyRepository->findBy(['code' => $code]);
//        foreach ($currencyies as $currency) {
//            $id = $currency->getId();
//        }

        $id = $currency[0]->getId();
        var_dump($id);
//        dd($id);
        return $id;
    }
}