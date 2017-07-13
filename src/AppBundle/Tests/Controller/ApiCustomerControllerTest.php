<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:25
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Customer;

class ApiCustomerControllerTest extends BasicControllerTest
{
    public function testShow()
    {
        $client = static::createClient();

        $customer = $client->getContainer()->get("doctrine.orm.entity_manager")->getRepository("AppBundle:Customer")->findOneBy([]);

        if (!$customer instanceof Customer) {
            throw new \Error("At least one customer needs to be created in order to conduct these tests.");
        }

        $crawler = $client->request('GET', '/api/customers/'. $customer->getId(), [], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/customers', [
            "keyword" => "test",
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

        $email = uniqid() . "functionnaltest@gmx.com";

        $data = json_encode([
            "email" => $email,
            "plain_password" => "functionnaltest",
            "firstname" => "Functionnal",
            "lastname" => "Test",
            "telephone" => "0102030405",
            "address" => "12 avenue de l'Arche, 49100 Angers",
        ]);

        $crawler = $client->request('POST', '/api/customers/add', [], array(), [
            "CONTENT_TYPE" => "application/json",
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ], $data);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());

        if ($client->getResponse()->isSuccessful()) {
            $em = $client->getContainer()->get("doctrine.orm.entity_manager");
            $customer = $em->getRepository("AppBundle:Customer")->findOneBy(["email" => $email]);
            $em->remove($customer);
            $em->flush();
        }
    }


}