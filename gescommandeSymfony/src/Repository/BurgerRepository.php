<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Burger|null find($id, $lockMode = null, $lockVersion = null)
 * @method Burger|null findOneBy(array $criteria, array $orderBy = null)
 * @method Burger[]    findAll()
 * @method Burger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    /**
     * Trouve les burgers les plus vendus du jour
     */
    public function getBurgersPlusVendusDuJour(): array
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        // Nouvelle syntaxe Doctrine DBAL 3.x
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT 
                b.id,
                b.nom,
                b.prix,
                COALESCE(SUM(lc.quantite), 0) as total_quantite,
                COALESCE(SUM(lc.quantite * lc.prix_unitaire), 0) as chiffre_affaires
            FROM burger b
            LEFT JOIN ligne_commande lc ON lc.produit_id = b.id AND lc.type_produit = \'burger\'
            LEFT JOIN commande c ON lc.commande_id = c.id 
                AND c.date_commande BETWEEN :debut AND :fin 
                AND c.etat != \'annulee\'
            GROUP BY b.id, b.nom, b.prix
            ORDER BY total_quantite DESC
            LIMIT 10
        ';

        // CORRECTION : Utilisation de executeQuery() au lieu de execute()
        $result = $conn->executeQuery($sql, [
            'debut' => $todayStart->format('Y-m-d H:i:s'),
            'fin' => $todayEnd->format('Y-m-d H:i:s')
        ]);

        return $result->fetchAllAssociative();
    }

    /**
     * Top 5 burgers vendus du jour
     */
    public function getTop5BurgersVendusDuJour(): array
    {
        $burgers = $this->getBurgersPlusVendusDuJour();
        return array_slice($burgers, 0, 5);
    }

    // Méthode alternative avec Query Builder (si vous préférez)
    public function getBurgersPlusVendusDuJourQB(): array
    {
        $todayStart = new \DateTime('today');
        $todayEnd = new \DateTime('today 23:59:59');

        return $this->createQueryBuilder('b')
            ->select([
                'b.id',
                'b.nom',
                'b.prix',
                'COALESCE(SUM(lc.quantite), 0) as total_quantite',
                'COALESCE(SUM(lc.quantite * lc.prix_unitaire), 0) as chiffre_affaires'
            ])
            ->leftJoin('App\Entity\LigneCommande', 'lc', 'WITH', 
                'lc.produit_id = b.id AND lc.type_produit = :burgerType')
            ->leftJoin('lc.commande', 'c', 'WITH', 
                'c.date_commande BETWEEN :debut AND :fin AND c.etat != :etat')
            ->setParameter('burgerType', 'burger')
            ->setParameter('debut', $todayStart)
            ->setParameter('fin', $todayEnd)
            ->setParameter('etat', 'annulee')
            ->groupBy('b.id')
            ->orderBy('total_quantite', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}