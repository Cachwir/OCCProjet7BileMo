<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:20
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Customer;

class CustomerRepositoryTest extends BasicRepositoryTest
{
    public function createDefaultEntities($name, $quantity)
    {
        $customers = [];

        $firstname = "Charles";
        $lastname = "Henri";
        $plainPassword = "testoss";
        $telephone = "01023500214";


        for ($i = 0; $i < $quantity; $i++) {
            $customer = new Customer();
            $customer->setFirstname($firstname);
            $customer->setLastname($lastname);
            $customer->setEmail($i . $name);
            $customer->setPlainPassword($plainPassword);
            $customer->setTelephone($telephone);

            $this->em->persist($customer);
            $this->em->flush();

            $customers[] = $customer;
        }

        return $customers;
    }

    public function testSearch()
    {
        $random_email = "testa-unitera-" . uniqid() . "@mymailbox.com";

        $quantity = 17;

        $customers = $this->createDefaultEntities($random_email, $quantity);

        try {
            $term = $random_email;
            $order = "desc";
            $limit = 5;
            $page = 4;

            $pager = $this->em
                ->getRepository(Customer::class)
                ->search($term, $order, $limit, $page)
            ;

            $this->assertEquals($page, $pager->getCurrentPage(), "The current customer page doesn't match.");
            $this->assertEquals($limit, $pager->getMaxPerPage(), "The number of customer per page doesn't match.");
            $this->assertEquals(ceil($quantity / $limit), $pager->getNbPages(), "The number of customer pages doesn't match.");
            $this->assertEquals($quantity, $pager->getNbResults(), "The number of customers doesn't match.");
        } finally {
            $this->deleteDefaultEntities($customers);
        }
    }
}