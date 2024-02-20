<?php

namespace App\Repository;

use App\Entity\Exchange;
use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

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

    public function findDate($importat): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.importAt = :importat')
            ->setParameter('importat', $importat)
            ->orderBy('e.importAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function insertExchange(float $mid, string $effectiveDate, int $sourceId, int $currencyId)
    {
        $conn = $this->getEntityManager()->getConnection();

//        $select = "SELECT id FROM currency WHERE code=:code";
//
//        $r = $conn->executeQuery($select, ['code' => $code]);
//        $id = $r->fetchAllAssociative();
//        $currencyId = $id[0]['id'];


        $query = "INSERT INTO exchange (mid, import_at, source_id, currency_id)
                    VALUES(:mid, :import_at, :source_id, :currency_id)";

        $result = $conn->executeQuery($query, [
            'mid' => $mid,
            'import_at' => $effectiveDate,
            'source_id' => $sourceId,
            'currency_id' => $currencyId,
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
