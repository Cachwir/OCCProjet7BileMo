<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:20
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Product;

class ProductRepositoryTest extends BasicRepositoryTest
{
    public function createDefaultEntities($name, $quantity)
    {
        $products = [];

        $brand = "Maxwell";
        $price = 100.99;
        $description = "Un super téléphone new age";
        $advantages = [
            "Longue batterie",
            "Léger",
            "Customisable et modulable",
        ];
        $caracteristics = [
            "caractéristiques générales" => [
                "ram" => "2Go",
                "processeur" => "Intel i7",
            ] ,
            "autres caractéristiques" => [
                "fait le café" => "oui",
            ],
        ];
        $available = true;

        for ($i = 0; $i < $quantity; $i++) {
            $product = new Product();
            $product->setName($name . $i);
            $product->setBrand($brand);
            $product->setPrice($price);
            $product->setDescription($description);
            $product->setAdvantages($advantages);
            $product->setCaracteristics($caracteristics);
            $product->setAvailable($available);

            $this->em->persist($product);
            $this->em->flush();

            $products[] = $product;
        }

        return $products;
    }

    public function testSearch()
    {
        $random_name = "TemporaryTestProduct_" . uniqid();

        $quantity = 12;

        $products = $this->createDefaultEntities($random_name, $quantity);

        try {
            $term = $random_name;
            $order = "desc";
            $limit = 3;
            $page = 3;

            $pager = $this->em
                ->getRepository(Product::class)
                ->search($term, $order, $limit, $page)
            ;

            $this->assertEquals($page, $pager->getCurrentPage(), "The current product page doesn't match.");
            $this->assertEquals($limit, $pager->getMaxPerPage(), "The number of product per page doesn't match.");
            $this->assertEquals(ceil($quantity / $limit), $pager->getNbPages(), "The number of product pages doesn't match.");
            $this->assertEquals($quantity, $pager->getNbResults(), "The number of products doesn't match.");
        } finally {
            $this->deleteDefaultEntities($products);
        }
    }
}