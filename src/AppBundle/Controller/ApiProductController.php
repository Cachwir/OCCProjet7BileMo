<?php
/**
 * Created by PhpStorm.
 * User: cachwir
 * Date: 19/06/17
 * Time: 16:27
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Nelmio\ApiDocBundle\Annotation as Doc;

class ApiProductController extends FOSRestController
{
    /**
     * @Get(
     *     path = "/products/{id}",
     *     name = "api_product_show",
     *     requirements = {"id"="\d+"}
     * )
     * @View(
     *     statusCode = 200,
     * )
     * @ParamConverter("product")
     *
     * @Doc\ApiDoc(
     *     resource=true,
     *     section="Products",
     *     description="Returns one target product.",
     *     requirements={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "requirements"="\d+",
     *             "description"="The product's unique identifier."
     *         }
     *     },
     *     statusCodes={
     *         200="Returned when ok",
     *         404="Returned when the requested product doesn't exist",
     *     }
     * )
     */
    public function showAction(Product $product)
    {
        return $product;
    }

    /**
     * @Get("/products", name="api_product_list")
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
     *     section="Products",
     *     description="Returns a list of products, paginated and ordered by name.",
     *     statusCodes={
     *         200="Returned when ok",
     *         400="Returned when the parameters are not correct",
     *         404="Returned when the requested page doesn't exist",
     *     }
     * )
     */
    public function listAction(ParamFetcherInterface $paramFetcher)
    {
        $pager = $this->getDoctrine()->getRepository('AppBundle:Product')->search(
            $paramFetcher->get('keyword'),
            $paramFetcher->get('order'),
            $paramFetcher->get('limit'),
            $paramFetcher->get('page')
        );

        $paginatedRepresentation = new PaginatedRepresentation(
            new CollectionRepresentation(
                $pager->getCurrentPageResults(),
                'products', // embedded rel
                'products'  // xml element name
            ),
            'api_product_list', // route
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
}