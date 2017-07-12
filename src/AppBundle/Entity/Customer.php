<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CustomerRepository")
 * @UniqueEntity(fields={"email"}, message="This email is already taken.")
 * @ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_customer_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_customer_list",
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "add",
 *      href = @Hateoas\Route(
 *          "api_customer_add",
 *          absolute = true
 *      )
 * )
 */
class Customer implements UserInterface
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
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", unique=true)
     *
     * @Serializer\Since("1.0")
     *
     * @Expose
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     *
     * @Expose
     */
    protected $password;

    /**
     * A non-persisted field used to create the encoded password.
     *
     * @Assert\NotBlank()
     * @SerializedName("plain_password")
     * @Type("string")
     *
     * @Expose
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     *
     * @Expose
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Expose
     */
    protected $telephone;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Expose
     */
    protected $address;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        // nope
    }

    public function getUsername()
    {
        return $this->getEmail();
    }


}