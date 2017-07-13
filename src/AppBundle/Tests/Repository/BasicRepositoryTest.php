<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:20
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BasicRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    abstract public function createDefaultEntities($name, $quantity);

    public function deleteDefaultEntities($entities)
    {
        foreach ($entities as $entity) {
            $this->em->remove($entity);
            $this->em->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}