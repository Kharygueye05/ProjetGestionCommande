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

    public function getTopProduitsVendusDuJour(): array
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        $query = $this->getEntityManager()->createQuery('
            SELECT 
                lc.produit_nom as nom,
                SUM(lc.quantite) as quantite_vendue,
                lc.type_produit as type
            FROM App\Entity\LigneCommande lc
            JOIN lc.commande c
            WHERE c.date_commande BETWEEN :debut AND :fin
            AND c.etat != :etat_annulee
            GROUP BY lc.produit_id, lc.produit_nom, lc.type_produit
            ORDER BY quantite_vendue DESC
        ')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat_annulee', 'annulee');

        $result = $query->getResult();
        
        $topProduits = array_slice($result, 0, 4);
        $maxQuantite = $topProduits ? max(array_column($topProduits, 'quantite_vendue')) : 1;
        
        foreach ($topProduits as &$produit) {
            $produit['pourcentage'] = $maxQuantite > 0 ? ($produit['quantite_vendue'] / $maxQuantite) * 100 : 0;
        }
        
        return $topProduits;
    }

    public function findCommandesRecentes(int $limit = 5): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.ligneCommandes', 'lc')
            ->addSelect('lc')
            ->leftJoin('c.client', 'cli')
            ->leftJoin('cli.user', 'user')
            ->orderBy('c.date_commande', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countCommandesEnCoursDuJour(): int
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_commande BETWEEN :debut AND :fin')
            ->andWhere('c.etat IN (:etats)')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etats', ['reçue', 'preparation'])
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (int) $result : 0;
    }

    public function countCommandesTermineesDuJour(): int
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_commande BETWEEN :debut AND :fin')
            ->andWhere('c.etat = :etat')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat', 'terminee')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (int) $result : 0;
    }

    public function countCommandesAnnuleesDuJour(): int
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        $result = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.date_commande BETWEEN :debut AND :fin')
            ->andWhere('c.etat = :etat')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat', 'annulee')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (int) $result : 0;
    }

    public function getRecettesDuJour(): float
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.montant)')
            ->where('c.date_commande BETWEEN :debut AND :fin')
            ->andWhere('c.etat != :etat')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat', 'annulee')
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float) $result : 0.0;
    }
}