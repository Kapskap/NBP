<?php

namespace App\Repository;

use App\Entity\Exchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exchange>
 *
 * @method Exchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exchange[]    findAll()
 * @method Exchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    /**
     * @return Currency[] Returns an array of Currency objects
     */
    public function findByDate($date): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.importAt = :date')
            ->setParameter('date', $date)
            ->orderBy('e.mid', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findCurrency($currency): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.currency = :currency')
            ->setParameter('currency', $currency)
            ->orderBy('e.importAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function insertExchange(string $currency, string $code, float $mid, string $effectiveDate)
    {
        $conn = $this->getEntityManager()->getConnection();

        $query = "INSERT INTO exchange (currency, code, mid, import_at)
                    VALUES(:currency, :code, :mid, :import_at)";

        $result = $conn->executeQuery($query, [
            'currency' => $currency,
            'code' => $code,
            'mid' => $mid,
            'import_at' => $effectiveDate,
        ]);

    }

//    public function findOneBySomeField($value): ?Exchange
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
