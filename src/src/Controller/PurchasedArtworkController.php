<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Interfaces\PurchasedArtworkServiceInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[Route('api/artwork', name: 'api.artwork')]
class PurchasedArtworkController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/buy', name:'.buy', methods: ['POST'])]
    public function buyArtworkAction(
        #[MapRequestPayload] BuyArtworkRequest $request, 
        PurchasedArtworkServiceInterface $service
    ): JsonResponse
    {
        //for a type problem
        $user = $this->getUser();
        if(!$user instanceof User) { 
            //probably never catch this
            throw new AccessDeniedException();
        }

        try {
            $result = $service->buyArtwork($request, $user);
        } catch(AlreadyBuyedException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if($result instanceof PurchasedArtwork) {
            return new JsonResponse($result);
        }

        return new JsonResponse(
            $result,
            400,
        );
    }
}