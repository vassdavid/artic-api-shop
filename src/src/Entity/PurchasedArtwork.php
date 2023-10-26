<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PurchasedArtworkRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PurchasedArtworkRepository::class)]
class PurchasedArtwork
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::INTEGER, unique: true)]
    private ?int $artworkId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /** @return self */
    public function setUser(?User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getArtworkId(): ?int
    {
        return $this->artworkId;
    }

    /** @return self */
    public function setArtworkId(?int $artworkId)
    {
        $this->artworkId = $artworkId;

        return $this;
    }

}
