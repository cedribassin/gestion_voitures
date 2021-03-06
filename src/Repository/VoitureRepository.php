<?php

namespace App\Repository;

use App\Entity\RechercheVoiture;
use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voiture::class);
    }

    public function findAllWithPagination(RechercheVoiture $rechercheVoiture):Query
    {
        //On renvoie juste la query pas le résultat (qui sera lui géré au niveau du controller)
        $req =  $this->createQueryBuilder('v');
        //Si on a l'information minAnnee qui est rempli alors on rajoute la close qui suit
        if ($rechercheVoiture->getMinAnnee()){
            // on récupère les voitures dont l'année est sup à getMinAnnee()
            $req = $req->andWhere('v.annee >= :min')
            ->setParameter(':min', $rechercheVoiture->getMinAnnee());
        }
        //Si on a l'information minAnnee qui est rempli alors on rajoute la close qui suit
        if ($rechercheVoiture->getMaxAnnee()){
            // on récupère les voitures dont l'année est inf à getMaxAnnee()
            $req = $req->andWhere('v.annee <= :max')
            ->setParameter(':max', $rechercheVoiture->getMaxAnnee());
        }

        return $req->getQuery();
    }

    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
