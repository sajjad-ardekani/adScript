<?php

namespace AppBundle\Entity;

/**
 * AdsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository {

    public function getCategoiress() {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.id', 'DESC');


        return $qb->getQuery()
                        ->getResult();
    }

}
