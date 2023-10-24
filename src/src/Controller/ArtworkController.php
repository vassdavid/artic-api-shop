<?php
namespace App\Controller;

use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArtworkServiceInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route(path:'/artwork', name: 'artwork.')]
class ArtworkController extends AbstractController
{
    #[Route(path:"/show", name:"show")]
    public function showAction(#[MapQueryString] ShowArtworkRequest $request, ArtworkServiceInterface $service): JsonResponse
    {
        $result = $service->showArtwork($request);

        return new JsonResponse($result);
    }

    #[Route(path:"/list", name:"list")]
    public function listAction(#[MapQueryString] ListArtworkRequest $request, ArtworkServiceInterface $service): JsonResponse
    {
        $result = $service->listArtwork($request);

        return new JsonResponse($result);
    }

}