<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 19/06/17
 * Time: 16:27
 */

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;

class ApiHomeController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/",
     *     name = "api_home"
     * )
     */
    public function homeAction()
    {
        return $this->redirectToRoute("nelmio_api_doc_index");
    }
}