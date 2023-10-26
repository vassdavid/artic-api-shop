<?php
namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ListArtworkRequest
{
    #[Assert\Positive]
    public int $page = 1;

    #[Assert\Positive]
    public int $limit = 20;
}