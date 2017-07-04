<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_product_list",
 *          absolute = true
 *      )
 * )
 *
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @Expose
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $brand;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $price;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $description;

    /**
     * @ORM\Column(type="json_array")
     *
     * @Expose
     */
    protected $advantages;

    /**
     * @ORM\Column(type="json_array")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $caracteristics;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $available;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function setUpdatedAtNow()
    {
        $this->setUpdatedAt(date("d/m/Y H:i:s"));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAdvantages()
    {
        return $this->advantages;
    }

    /**
     * @param mixed $advantages
     */
    public function setAdvantages($advantages)
    {
        $this->advantages = $advantages;
    }

    /**
     * @return mixed
     */
    public function getCaracteristics()
    {
        return $this->caracteristics;
    }

    /**
     * @param mixed $caracteristics
     */
    public function setCaracteristics($caracteristics)
    {
        $this->caracteristics = $caracteristics;
    }

    /**
     * @return mixed
     */
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available)
    {
        $this->available = $available;
    }
}