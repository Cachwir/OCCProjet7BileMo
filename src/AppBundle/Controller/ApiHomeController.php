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

class ApiHomeController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/api",
     *     name = "api_home"
     * )
     */
    public function homeAction()
    {
        return $this->redirectToRoute("nelmio_api_doc_index");
    }

    /**
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Authentication",
     *     description="Allows you to log into the API for 30 minutes by default (can be configured in the app's parameters for all users)",
     *     requirements={
     *         {
     *             "name"="_username",
     *             "dataType"="string",
     *             "description"="The user's unique username."
     *         },
     *         {
     *             "name"="_password",
     *             "dataType"="string",
     *             "description"="The user's unhashed password."
     *         },
     *     },
     *     statusCodes={
     *         200="Returned when successfully logged in. The temporary token is returned and needs to be used in further requests.",
     *         401="Bad credentials",
     *     }
     * )
     */
    public function loginAction()
    {
        throw new \LogicException("You are not supposed to reach this line of code");
    }
}