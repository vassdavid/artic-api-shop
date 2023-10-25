<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Email]
    #[ORM\Column(type: Types::STRING, length:255, nullable: false, unique: true)]
    private ?string $email;


    #[ORM\Column(type: Types::STRING, length: 64)]
    private ?string $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->email = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return  self
     */ 
    public function setEmail(?string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**

     * @return  self
     */ 
    public function setPassword(?string $password)
    {
        $this->password = $password;

        return $this;
    }
}
