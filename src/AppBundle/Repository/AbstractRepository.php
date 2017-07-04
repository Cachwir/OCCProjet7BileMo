<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 01/07/17
 * Time: 16:59
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends EntityRepository
{
    protected function paginate(QueryBuilder $qb, $limit = 20, $page = 1)
    {
        if (1 > $limit || 1 > $page) {
            throw new \LogicException('$limit & $page must be greater than 0.', 400);
        }

        $offset = ($page - 1) * $limit;

        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $currentPage = ceil(($offset + 1) / $limit);
        $pager->setCurrentPage($currentPage);
        $pager->setMaxPerPage((int) $limit);

        return $pager;
    }
}
