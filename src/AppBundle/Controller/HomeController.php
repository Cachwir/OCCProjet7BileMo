<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 19/06/17
 * Time: 16:27
 */

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation as Doc;

class HomeController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/",
     *     name = "home"
     * )
     */
    public function homeAction()
    {
        return $this->redirectToRoute("nelmio_api_doc_index");
    }
}