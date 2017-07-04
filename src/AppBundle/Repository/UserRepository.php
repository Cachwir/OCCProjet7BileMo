<?php

namespace AppBundle\Repository;

class UserRepository extends AbstractRepository
{
    public function search($term, $order = 'asc', $limit = 20, $page = 1)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.email', $order)
        ;

        if ($term) {
            $qb
                ->where('u.email LIKE ?1')
                ->setParameter(1, '%'.$term.'%')
            ;
        }

        return $this->paginate($qb, $limit, $page);
    }
}