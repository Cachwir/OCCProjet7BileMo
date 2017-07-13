<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 12/07/17
 * Time: 18:17
 */

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BasicControllerTest extends WebTestCase
{
    protected $testApiToken;

    public function getTestApiToken() {
        if ($this->testApiToken === null) {
            $client = static::createClient();

            $username = $client->getContainer()->getParameter("user_test.username");
            $password = $client->getContainer()->getParameter("user_test.password");

            $client->request(
                'POST',
                '/api/login_check',
                array(
                    '_username' => $username,
                    '_password' => $password,
                )
            );

            $data = json_decode($client->getResponse()->getContent(), true);

            $this->testApiToken = $data["token"];
        }

        return $this->testApiToken;
    }
}