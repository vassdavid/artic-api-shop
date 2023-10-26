<?php
namespace App\Interfaces;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Response\Artic\ArticListResponse;
use App\Exception\InvalidApiResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

interface ArticApiServiceInterface
{
    /**
     * Retrival single artwork
     *
     * @param ShowArtworkRequest $request
     * 
     * @throws InvalidApiResponseException
     * @throws NotFoundHttpException
     * @throws HttpException
     * 
     * @return Artwork
     */
    public function retrievalArtwork(ShowArtworkRequest $request): ?Artwork;
    
    /**
     * List array of artwork based in request
     *
     * @param ListArtworkRequest $request
     * 
     * @throws InvalidApiResponseException
     * @throws NotFoundHttpException
     * @throws HttpException
     * 
     * @return ArticListResponse
     */
    public function retrivalArtworkList(ListArtworkRequest $request): ArticListResponse;
}