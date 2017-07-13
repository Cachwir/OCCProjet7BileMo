<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:25
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;

class ApiUserControllerTest extends BasicControllerTest
{
    public function testShow()
    {
        $client = static::createClient();

        $user = $client->getContainer()->get("doctrine.orm.entity_manager")->getRepository("AppBundle:User")->findOneBy([]);

        if (!$user instanceof User) {
            throw new \Error("At least one user needs to be created in order to conduct these tests.");
        }

        $crawler = $client->request('GET', '/api/users/'. $user->getId(), [], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/users', [
            "order" => "desc",
            "limit" => "5",
            "page" => "1",
        ], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testAdd()
    {
        $client = static::createClient();

        $username = "functionnaltest";

        $data = json_encode([
            "username" => $username,
            "plain_password" => "functionnaltest",
            "role" => User::ROLE_USER,
            "firstname" => "Functionnal",
            "lastname" => "Test",
            "comment" => "This is a functionnal test account. If you see it, please delete it.",
        ]);

        $crawler = $client->request('POST', '/api/users/add', [], array(), [
            "CONTENT_TYPE" => "application/json",
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ], $data);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testDelete()
    {
        $client = static::createClient();

        $username = "functionnaltest";

        $user = $client->getContainer()->get("doctrine.orm.entity_manager")->getRepository("AppBundle:User")->findOneBy(["username" => $username]);

        $crawler = $client->request('GET', '/api/users/'. $user->getId() . '/delete', [], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }


}