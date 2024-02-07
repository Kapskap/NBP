<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Exchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

//    public function insertExchange(string $currency, string $code, float $mid, string $effectiveDate)
//    {
////        $em = $this->entityManager;
//        $conn = $this->getEntityManager()->getConnection();
//
//        $query = "INSERT INTO exchange (currency, code, mid, import_at)
//                    VALUES(:currency, :code, :mid, :import_at)";
//
////        $stmt = $em->getConnection()->prepare($query);
//        $result = $conn->executeQuery($query, [
//            'currency' => $currency,
//            'code' => $code,
//            'mid' => $mid,
//            'import_at' => $effectiveDate,
//        ]);
//
////        $r = $stmt->execute(array(
////            'currency' => $currency,
////            'code' => $code,
////            'mid' => $mid,
////            'import_at' => $effectiveDate,
////        ));
//    }
}