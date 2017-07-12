<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 19/06/17
 * Time: 16:27
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Customer;
use AppBundle\Exception\ResourceValidationException;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;

class ApiCustomerController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/api/customers/{id}",
     *     name = "api_customer_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View(
     *     statusCode = 200,
     * )
     * @ParamConverter("customer")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Customers",
     *     description="Returns one target customer.",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The customer's unique identifier."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when ok",
     *         401="No token was provided or the token is invalid",
     *         404="Returned when the requested customer doesn't exist",
     *     }
     * )
     */
    public function showAction(Customer $customer)
    {
        return $customer;
    }

    /**
     * @Get("/api/customers", name="api_customer_list")
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
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Customers",
     *     description="Returns a list of customers, paginated and ordered by email.",
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
        $pager = $this->getDoctrine()->getRepository('AppBundle:Customer')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );

        $paginatedRepresentation = new PaginatedRepresentation(
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'customers', // embedded rel
                'customers'  // xml element name
            ),
            'api_customer_list', // route
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
     *     path = "/api/customers/add",
     *     name = "api_customer_add"
     * )
     * @View(
     *     statusCode = 201,
     * )
     * @ParamConverter(
     *     "customer",
     *     converter="fos_rest.request_body",
     * )
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Customers",
     *     description="Creates a customer.",
     *     requirements={
     *         {
     *             "name"="email",
     *             "dataType"="string",
     *             "description"="The customer's unique email."
     *         },
     *         {
     *             "name"="plain_password",
     *             "dataType"="string",
     *             "description"="The customer's unhashed password."
     *         },
     *         {
     *             "name"="firstname",
     *             "dataType"="string",
     *             "description"="The customer's first name."
     *         },
     *         {
     *             "name"="lastname",
     *             "dataType"="string",
     *             "description"="The customer's last name."
     *         },
     *         {
     *             "name"="telephone",
     *             "dataType"="string",
     *             "description"="The customer's phone number (optionnal)."
     *         },
     *         {
     *             "name"="address",
     *             "dataType"="string",
     *             "description"="The customer's full address (optionnal)."
     *         },
     *     },
     *     statusCodes={
     *         201="Returned when created",
     *         400="Returned when a violation is raised by validation",
     *         401="No token was provided or the token is invalid",
     *     }
     * )
     */
    public function addAction(Customer $customer, ConstraintViolationListInterface $violations)
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
        $em->persist($customer);
        $em->flush();

        return $this->view($customer, Response::HTTP_CREATED, ['Location' => $this->generateUrl('api_customer_show', ['id' => $customer->getId(), UrlGeneratorInterface::ABSOLUTE_URL])]);
    }
}