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
    public function findByDateAndSourceId($date, $sourceId): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.importAt = :date')
            ->andWhere('e.source = :sourceid')
            ->setParameter('date', $date)
            ->setParameter('sourceid', $sourceId)
            ->orderBy('e.mid', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByDate($date): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.importAt = :date')
            ->setParameter('date', $date)
            ->orderBy('e.mid', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findDateAndSourceId($importat, $sourceId): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.importAt = :importat')
            ->andWhere('e.source = :source')
            ->setParameter('importat', $importat)
            ->setParameter('source', $sourceId)
            ->orderBy('e.importAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function insertExchange(float $mid, string $midCode, string $effectiveDate, int $sourceId, int $currencyId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $query = "INSERT INTO exchange (mid, mid_code, import_at, source_id, currency_id)
                    VALUES(:mid, :mid_code, :import_at, :source_id, :currency_id)";

        $result = $conn->executeQuery($query, [
            'mid' => $mid,
            'mid_code' => $midCode,
            'import_at' => $effectiveDate,
            'source_id' => $sourceId,
            'currency_id' => $currencyId,
        ]);

    }

}
