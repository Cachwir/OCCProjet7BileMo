<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:20
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\User;

class UserRepositoryTest extends BasicRepositoryTest
{
    public function createDefaultEntities($name, $quantity)
    {
        $users = [];

        $firstname = "Charles";
        $lastname = "Henri";
        $plainPassword = "testoss";
        $role = User::ROLE_USER;
        $comment = "This is a test user. Please, delete it if your happen to see it.";


        for ($i = 0; $i < $quantity; $i++) {
            $user = new User();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setRole($role);
            $user->setUsername($name . $i);
            $user->setPlainPassword($plainPassword);
            $user->setComment($comment);

            $this->em->persist($user);
            $this->em->flush();

            $users[] = $user;
        }

        return $users;
    }

    public function testSearch()
    {
        $random_username = "alejuandro-" . uniqid();

        $quantity = 11;

        $users = $this->createDefaultEntities($random_username, $quantity);

        try {
            $term = $random_username;
            $order = "desc";
            $limit = 1;
            $page = 11;

            $pager = $this->em
                ->getRepository(User::class)
                ->search($term, $order, $limit, $page)
            ;

            $this->assertEquals($page, $pager->getCurrentPage(), "The current user page doesn't match.");
            $this->assertEquals($limit, $pager->getMaxPerPage(), "The number of user per page doesn't match.");
            $this->assertEquals(ceil($quantity / $limit), $pager->getNbPages(), "The number of user pages doesn't match.");
            $this->assertEquals($quantity, $pager->getNbResults(), "The number of users doesn't match.");
        } finally {
            $this->deleteDefaultEntities($users);
        }
    }
}