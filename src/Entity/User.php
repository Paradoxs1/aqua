<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 28.02.2018
 * Time: 10:42
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account!")
 */
class User implements UserInterface
{
    public function __construct()
    {
        $this->studiedGenuses = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank(groups={"Registration"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isScientist = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $universityName;

    /**
     * @ORM\Column(type="string", nullable=true, length=255)
     */
    private $avatarUri;

    /**
     * @ORM\OneToMany(targetEntity="GenusScientist", mappedBy="user")
     */
    private $studiedGenuses;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="last_updated_by_id", referencedColumnName="id", nullable=true)
     */
    private $lastUpdatedBy;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getIsScientist()
    {
        return $this->isScientist;
    }

    /**
     * @param mixed $isScientist
     */
    public function setIsScientist($isScientist)
    {
        $this->isScientist = $isScientist;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUniversityName()
    {
        return $this->universityName;
    }

    /**
     * @param mixed $universityName
     */
    public function setUniversityName($universityName)
    {
        $this->universityName = $universityName;
    }

    /**
     * @return mixed
     */
    public function getAvatarUri()
    {
        return $this->avatarUri;
    }

    /**
     * @param mixed $avatarUri
     */
    public function setAvatarUri($avatarUri)
    {
        $this->avatarUri = $avatarUri;
    }

    public function getFullName()
    {
        return trim($this->getFirstName() . ' ' . $this->getLastName());
    }

    /**
     * @return ArrayCollection|GenusScientist[]
     */
    public function getStudiedGenuses()
    {
        return $this->studiedGenuses;
    }

    public function addStudiedGenus(Genus $genus)
    {
        if ($this->studiedGenuses->contains($genus)) {
            return;
        }

        $this->studiedGenuses[] = $genus;
        $genus->addGenusScientist($this);
    }

    public function removeStudiedGenus(Genus $genus)
    {
        if (!$this->studiedGenuses->contains($genus)) {
            return;
        }

        $this->studiedGenuses->removeElement($genus);
        $genus->removeGenusScientist($this);
    }

    public function __toString()
    {
        return (string) $this->getFullName() ? $this->getFullName() : $this->getEmail();
    }

    public function setFullName($fullName)
    {
        $names = explode(' ', $fullName);
        $firstName = array_shift($names);
        $lastName = implode(' ', $names);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
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

    /**
     * @return mixed
     */
    public function getLastUpdatedBy()
    {
        return $this->lastUpdatedBy;
    }

    /**
     * @param mixed $lastUpdatedBy
     */
    public function setLastUpdatedBy($lastUpdatedBy)
    {
        $this->lastUpdatedBy = $lastUpdatedBy;
    }
}