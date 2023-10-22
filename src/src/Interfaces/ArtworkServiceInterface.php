<?php
namespace App\Interfaces;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;

interface ArtworkServiceInterface
{
    public function showArtwork(ShowArtworkRequest $request): ?Artwork;

    /**
     * @return Artwork[]
     */
    public function listArtwork(ListArtworkRequest $request): array;
}