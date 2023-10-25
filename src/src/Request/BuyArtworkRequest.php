<?php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class BuyArtworkRequest
{
    #[Assert\NotNull]
    public ?int $artworkId;
}