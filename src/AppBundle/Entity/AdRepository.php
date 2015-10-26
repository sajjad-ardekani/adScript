<?php

namespace AppBundle\Entity;

/**
 * AdsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdRepository extends \Doctrine\ORM\EntityRepository {

    public function getAdsForBlog() {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC');
        return $qb->getQuery()
                        ->getResult();
    }

    public function getCategoryAd($blogId) {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->where('c.categories = :category_id')
                ->addOrderBy('c.creationDate')
                ->orderBy('c.id', 'DESC')
                ->setParameter('category_id', $blogId);
        return $qb->getQuery()
                        ->getResult();
    }

}
