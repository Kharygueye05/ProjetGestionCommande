<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * Trouve les menus les plus vendus du jour
     */
    public function getMenusPlusVendusDuJour(): array
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        // Nouvelle syntaxe Doctrine DBAL 3.x
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                m.id,
                m.nom,
                COALESCE(SUM(lc.quantite), 0) as total_quantite,
                COALESCE(SUM(lc.quantite * lc.prix_unitaire), 0) as chiffre_affaires
            FROM menu m
            LEFT JOIN ligne_commande lc ON lc.produit_id = m.id AND lc.type_produit = \'menu\'
            LEFT JOIN commande c ON lc.commande_id = c.id 
                AND c.date_commande BETWEEN :debut AND :fin 
                AND c.etat != \'annulee\'
            GROUP BY m.id, m.nom
            ORDER BY total_quantite DESC
            LIMIT 10
        ';

        // CORRECTION : Utilisation de executeQuery() au lieu de prepare() + execute()
        $result = $conn->executeQuery($sql, [
            'debut' => $todayStart->format('Y-m-d H:i:s'),
            'fin' => $todayEnd->format('Y-m-d H:i:s')
        ]);

        return $result->fetchAllAssociative();
    }

    /**
     * Méthode alternative avec Query Builder
     */
    public function getMenusPlusVendusDuJourQB(): array
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        return $this->createQueryBuilder('m')
            ->select([
                'm.id',
                'm.nom',
                'COALESCE(SUM(lc.quantite), 0) as total_quantite',
                'COALESCE(SUM(lc.quantite * lc.prix_unitaire), 0) as chiffre_affaires'
            ])
            ->leftJoin('App\Entity\LigneCommande', 'lc', 'WITH', 
                'lc.produit_id = m.id AND lc.type_produit = :menuType')
            ->leftJoin('lc.commande', 'c', 'WITH', 
                'c.date_commande BETWEEN :debut AND :fin AND c.etat != :etat')
            ->setParameter('menuType', 'menu')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat', 'annulee')
            ->groupBy('m.id')
            ->orderBy('total_quantite', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}