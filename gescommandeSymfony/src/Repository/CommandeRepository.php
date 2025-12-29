<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }


    public function createQueryByFilters(array $filters)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.ligneCommandes', 'lc')
            ->leftJoin('c.client', 'cli') 
            ->leftJoin('cli.user', 'user') 
            ->addSelect('lc')
            ->orderBy('c.date_commande', 'DESC');

        if (!empty($filters['client'])) {
            $qb->andWhere('user.nom LIKE :client OR user.prenom LIKE :client')
            ->setParameter('client', '%' . $filters['client'] . '%');
        }

        if (!empty($filters['etat'])) {
            $qb->andWhere('c.etat = :etat')
            ->setParameter('etat', $filters['etat']);
        }

        if (!empty($filters['type_commande'])) {
            $qb->andWhere('c.type_commande = :type_commande')
            ->setParameter('type_commande', $filters['type_commande']);
        }

        if (!empty($filters['produit_type']) && in_array($filters['produit_type'], ['burger', 'menu'])) {
            $qb->andWhere('lc.type_produit = :produit_type')
            ->setParameter('produit_type', $filters['produit_type']);
        }

        if (!empty($filters['date'])) {
            $date = \DateTime::createFromFormat('d/m/Y', $filters['date']);
            if ($date) {
                $dateDebut = clone $date;
                $dateFin = clone $date;
                $dateFin->modify('+1 day');
                $qb->andWhere('c.date_commande >= :date_debut AND c.date_commande < :date_fin')
                ->setParameter('date_debut', $dateDebut)
                ->setParameter('date_fin', $dateFin);
            }
        }

        return $qb->getQuery();
    }
}