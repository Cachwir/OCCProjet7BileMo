<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 11/07/17
 * Time: 12:20
 */

namespace tests\AppBundle\Entity;


use AppBundle\Entity\Product;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $product = new Product();
        $product->setBrand("Maxwell");

        $result = 42;

        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }
}