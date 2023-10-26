<?php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ShowArtworkRequest
{
    #[Assert\Positive]
    public int $id = 0;
}