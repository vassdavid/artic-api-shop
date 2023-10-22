<?php
namespace App\Interfaces;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;

interface ArticApiServiceInterface
{
    public function retrievalArtwork(ShowArtworkRequest $request): ?Artwork;
    
    /**
     * List array of artwork based in request
     *
     * @param ListArtworkRequest $request
     * @return Artwork[]
     */
    public function retrivalArtworkList(ListArtworkRequest $request): array;
}