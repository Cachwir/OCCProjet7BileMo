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
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"username"}, message="This username is already taken.")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ExclusionPolicy("all")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "api_user_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "list",
 *      href = @Hateoas\Route(
 *          "api_user_list",
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "add",
 *      href = @Hateoas\Route(
 *          "api_user_add",
 *          absolute = true
 *      )
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "api_user_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", unique=true)
     *
     * @Expose
     */
    protected $username;

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
    protected $role;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Expose
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Expose
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Expose
     */
    protected $comment;

    const ROLE_USER = "ROLE_USER";
    const ROLE_ADMIN = "ROLE_ADMIN";

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
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

    public function getSalt()
    {
        // bcrypt used so a salt is not needed
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
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
}