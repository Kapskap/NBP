<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Currency>
 *
 * @method Currency|null find($code, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function insertCurrency(string $code, string $namePL, string $nameEN)
    {
        $conn = $this->getEntityManager()->getConnection();

        $query = "INSERT INTO currency (code, name_pl, name_en)
                    VALUES(:code, :name_pl, :name_en, )";

        $result = $conn->executeQuery($query, [
            'code' => $code,
            'name_pl' => $namePL,
            'name_en' => $nameEN,
        ]);

    }

    public  function getIdByCode(string $code)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.code = :code')
            ->setParameter('code', $code)
//            ->orderBy('e.mid', 'DESC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return Language[] Returns an array of Language objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Language
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
