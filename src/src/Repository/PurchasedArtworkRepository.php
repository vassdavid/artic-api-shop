<?php

namespace App\Repository;

use App\Entity\PurchasedArtwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PurchasedArtwork>
 *
 * @method PurchasedArtwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchasedArtwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchasedArtwork[]    findAll()
 * @method PurchasedArtwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasedArtworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchasedArtwork::class);
    }

}
