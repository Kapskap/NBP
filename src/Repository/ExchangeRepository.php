<?php

namespace App\Repository;

use App\Entity\Exchange;
use App\Entity\Language;
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

    public function insertExchange(
        string $currency, string $code, float $mid, string $effectiveDate, int $sourceId)
    {
        $conn = $this->getEntityManager()->getConnection();

//        $select = "SELECT id FROM language WHERE code=:code";
//
//        $r = $conn->executeQuery($select, ['code' => $code]);
//        $id = $r->fetchAllAssociative();
//        $languageId = $id[0]['id'];


        $query = "INSERT INTO exchange (currency, code, mid, import_at, source_id, language_id)
                    VALUES(:currency, :code, :mid, :import_at, :source_id, 
                           (SELECT language.id FROM language WHERE code=:code))";

        $result = $conn->executeQuery($query, [
            'currency' => $currency,
            'code' => $code,
            'mid' => $mid,
            'import_at' => $effectiveDate,
            'source_id' => $sourceId,
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
