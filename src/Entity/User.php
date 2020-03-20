<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir un nom d'utilisateur.")
     * @Assert\Length(max="255")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Vous devez saisir une adresse email.")
     * @Assert\Email(message="Le format de l'adresse n'est pas correcte.")
     * @Assert\Length(max="255")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe.")
     * @Assert\Length(max="255")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="author")
     */
    private $tasks;

    /**
     * @ORM\Column(type="array")
     * @Assert\NotBlank(message="Vous devez saisir un role à l'utilisateur.")
     */
    private $roles = [];

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(){
        return NULL;
    }

    public function eraseCredentials(){}

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): ?Collection
    {
        return $this->tasks;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }
    
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
