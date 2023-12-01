<?php

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ad[]    findAll()
 * @method Ad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ad::class);
    }

    /**
     * @return Array[]
     */
    public function countByKeyWord(string $keyword, string $location): array
    {
        $entityManager = $this->getEntityManager();

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT count(ad.id)
        FROM ad 
        INNER JOIN sub_category subcat ON ad.sub_category_id = subcat.id 
                INNER JOIN category cat ON subcat.category_id = cat.id 
                INNER JOIN city cit ON cit.id = ad.city_id 
                INNER JOIN department dep ON dep.id = cit.department_id 
                INNER JOIN region reg ON reg.id = dep.region_id 
                WHERE ad.status_id = 1 
                AND (ad.title LIKE :keyword OR ad.content LIKE :keyword
                     OR cat.title LIKE :keyword OR subcat.title LIKE :keyword ) 
                     AND (ad.title LIKE :keyword OR ad.content LIKE :keyword
                     OR cat.title LIKE :keyword OR subcat.title LIKE :keyword ) 
                     AND ( cit.name LIKE :location OR cit.zip_code LIKE :location OR dep.name LIKE :location OR reg.name LIKE :location )
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['keyword' => $keyword , 'location' => $location ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetch();
    }

    /**
     * @return Array[]
     */
    public function findByKeyWordOrderByAntechrono(string $keyword, string $location, int $start, int $numberPerPage): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT ad.id, ad.title, ad.created_at, ad.status_id, ad.condition_ad_id, ad.ad_type_id, ad.sub_category_id, subcat.category_id, dep.number, ad.image_name
        FROM ad 
        INNER JOIN sub_category subcat ON ad.sub_category_id = subcat.id 
                INNER JOIN category cat ON subcat.category_id = cat.id 
                INNER JOIN city cit ON cit.id = ad.city_id 
                INNER JOIN department dep ON dep.id = cit.department_id 
                INNER JOIN region reg ON reg.id = dep.region_id 
                WHERE ad.status_id = 1 
                AND (ad.title LIKE :keyword OR ad.content LIKE :keyword
                     OR cat.title LIKE :keyword OR subcat.title LIKE :keyword ) 
                     AND ( cit.name LIKE :location OR cit.zip_code LIKE :location OR dep.name LIKE :location OR reg.name LIKE :location )
                     ORDER BY ad.created_at DESC  
                     LIMIT ' . $start . ', ' . $numberPerPage . '
            ';
        $stmt = $conn->prepare($sql);
        //Impossible de mettre en paramètres $start et $numberPerPage car par défault ils seront écrit avec des guillemets dans la requête, ceci provoque une erreur à l'éxécution, il faut donc bien s'assurer que ces variables sont des entiers, pour ne pas se retrouver avec des injections !!! Voir également les bundle proposé pour la pagination, pour une vraie sécurité !
        $stmt->execute(['keyword' => $keyword , 'location' =>  $location ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }



    // /**
    //  * @return Ad[] Returns an array of Ad objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ad
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
