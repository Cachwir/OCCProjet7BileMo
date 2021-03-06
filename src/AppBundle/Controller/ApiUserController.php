<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 19/06/17
 * Time: 16:27
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Exception\ResourceValidationException;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;

class ApiUserController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/api/users/{id}",
     *     name = "api_user_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View(
     *     statusCode = 200,
     * )
     * @ParamConverter("user")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="ADMIN - Returns one target user.",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The user's unique identifier."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when ok",
     *         401="No token was provided or the token is invalid",
     *         404="Returned when the requested user doesn't exist",
     *     }
     * )
     */
    public function showAction(User $user)
    {
        return $user;
    }

    /**
     * @Get("/api/users", name="api_user_list")
     * @QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=true,
     *     strict=true,
     *     description="The keyword to search for."
     * )
     * @QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     strict=true,
     *     description="Sort order (asc or desc)"
     * )
     * @QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default=15,
     *     strict=true,
     *     description="Max number of movies per page."
     * )
     * @QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default=1,
     *     strict=true,
     *     description="The desired page"
     * )
     * @View(
     *     statusCode = 200,
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="ADMIN - Returns a list of users, paginated and ordered by email.",
     *     statusCodes={
     *         200="Returned when ok",
     *         400="Returned when the parameters are not correct",
     *         401="No token was provided or the token is invalid",
     *         404="Returned when the requested page doesn't exist",
     *     }
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('AppBundle:User')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );

        $paginatedRepresentation = new PaginatedRepresentation(
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'users', // embedded rel
                'users'  // xml element name
            ),
            'api_user_list', // route
            array(), // route parameters
            $pager->getCurrentPage(),       // page number
            $pager->getMaxPerPage(),      // limit
            $pager->getNbPages(),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional
            true,
            $pager->getNbResults()
        );

        return $paginatedRepresentation;
    }

    /**
     * @Post(
     *     path = "/api/users/add",
     *     name = "api_user_add"
     * )
     * @View(
     *     statusCode = 201,
     * )
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     * )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="ADMIN - Creates a user. Be careful when specifying the role. If ROLE_ADMIN is chosen, the user will be able to see, add and delete users.",
     *     requirements={
     *         {
     *             "name"="username",
     *             "dataType"="string",
     *             "description"="The user's unique username."
     *         },
     *         {
     *             "name"="plain_password",
     *             "dataType"="string",
     *             "description"="The user's unhashed password."
     *         },
     *         {
     *             "name"="role",
     *             "dataType"="string",
     *             "description"="The user's role (ROLE_USER = user, ROLE_ADMIN = admin). Admins can view, add and delete users."
     *         },
     *         {
     *             "name"="firstname",
     *             "dataType"="string",
     *             "description"="The user's first name (optionnal)."
     *         },
     *         {
     *             "name"="lastname",
     *             "dataType"="string",
     *             "description"="The user's last name (optionnal)."
     *         },
     *         {
     *             "name"="comment",
     *             "dataType"="string",
     *             "description"="A comment to describe the user (optionnal)."
     *         },
     *     },
     *     statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation",
     *         401="No token was provided or the token is invalid",
     *     }
     * )
     */
    public function addAction(User $user, ConstraintViolationListInterface $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            $i = 0;
            foreach ($violations as $violation) {
                if ($i !== 0) {
                    $message .= " / ";
                }
                $message .= sprintf("Field %s: %s", $violation->getPropertyPath(), $violation->getMessage());
                $i++;
            }

            throw new ResourceValidationException($message);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view($user, Response::HTTP_CREATED, ['Location' => $this->generateUrl('api_user_show', ['id' => $user->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }

    /**
     * @Get(
     *     path = "/api/users/{id}/delete",
     *     name = "api_user_delete",
     *     requirements = {"id"="\d+"}
     * )
     * @View(
     *     statusCode = 200,
     * )
     * @ParamConverter("user")
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Users",
     *     description="ADMIN - Deletes target user.",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The user's unique identifier."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when deleted successfully",
     *         401="No token was provided or the token is invalid",
     *         404="Returned when the requested user doesn't exist",
     *     }
     * )
     */
    public function deleteAction(User $user)
    {
        $em = $this->get("doctrine.orm.entity_manager");
        $em->remove($user);
        $em->flush();
    }
}