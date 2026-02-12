<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
	public function findForAdminIndex(User $user, bool $isAdmin): array
	{
		$querybuilder = $this->createQueryBuilder('a')
			->leftJoin('a.category', 'c')->addSelect('c')
			->leftJoin('a.user', 'u')->addSelect('u')
			->orderBy('a.createdAt', 'DESC');

		if (!$isAdmin) {
			$querybuilder->andWhere('a.user = :user')
			->setParameter('user', $user);
		}

		return $querybuilder->getQuery()->getResult();
	}

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
