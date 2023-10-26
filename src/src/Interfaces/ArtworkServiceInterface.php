<?php
namespace App\Interfaces;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Response\Artic\ArticListResponse;

interface ArtworkServiceInterface
{
    public function showArtwork(ShowArtworkRequest $request): ?Artwork;

    public function listArtwork(ListArtworkRequest $request): ArticListResponse;
}