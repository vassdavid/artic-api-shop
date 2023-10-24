<?php
namespace App\Response\Artic;

use App\Dto\Artwork;
use App\Response\Artic\ListPagination;

class ArticListResponse extends ListPagination
{
    /**
     * List of artworks
     * @var Artwork[]
     */
    public array $data = [];
}