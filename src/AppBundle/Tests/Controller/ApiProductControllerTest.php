<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:25
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiProductControllerTest extends BasicControllerTest
{
    public function testShow()
    {
        $client = static::createClient();

        $product = $client->getContainer()->get("doctrine.orm.entity_manager")->getRepository("AppBundle:Product")->findOneBy([]);

        if (!$product instanceof Product) {
            throw new \Error("At least one product needs to be created in order to conduct these tests.");
        }

        $crawler = $client->request('GET', '/api/products/'. $product->getId(), [], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/products', [
            "keyword" => "product",
            "order" => "desc",
            "limit" => "5",
            "page" => "1",
        ], array(), [
            "HTTP_AUTHORIZATION" => "Bearer ". $this->getTestApiToken(),
        ]);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    }
}