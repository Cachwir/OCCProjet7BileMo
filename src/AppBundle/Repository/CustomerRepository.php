<?php

namespace AppBundle\Repository;

class CustomerRepository extends AbstractRepository
{
    public function search($term, $order = 'asc', $limit = 20, $page = 1)
    {
        $qb = $this
            ->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.email', $order)
        ;

        if ($term) {
            $qb
                ->where('c.email LIKE ?1')
                ->setParameter(1, '%'.$term.'%')
            ;
        }

        return $this->paginate($qb, $limit, $page);
    }
}