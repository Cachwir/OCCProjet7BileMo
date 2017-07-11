<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:25
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiUserControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $user = $client->getContainer()->get("doctrine.orm.entity_manager")->getRepository("AppBundle:User")->findOneBy([]);

        if (!$user instanceof User) {
            throw new \Error("At least one user needs to be created in order to conduct these tests.");
        }

        $crawler = $client->request('GET', '/users/'. $user->getId());

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users', [
            "keyword" => "user",
            "order" => "desc",
            "limit" => "5",
            "page" => "1",
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testAdd()
    {
        $client = static::createClient();

        $email = uniqid() . "functionnaltest@gmx.com";

        $data = json_encode([
            "email" => $email,
            "plain_password" => "functionnaltest",
            "firstname" => "Functionnal",
            "lastname" => "Test",
            "telephone" => "0102030405",
            "address" => "12 avenue de l'Arche, 49100 Angers",
        ]);

        $crawler = $client->request('POST', '/users/add', [], array(), [
            "CONTENT_TYPE" => "application/json",
        ], $data);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());

        if ($client->getResponse()->isSuccessful()) {
            $em = $client->getContainer()->get("doctrine.orm.entity_manager");
            $user = $em->getRepository("AppBundle:User")->findOneBy(["email" => $email]);
            $em->remove($user);
            $em->flush();
        }
    }


}