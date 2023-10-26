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
use App\Transform\ConstraintViolationListTransform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            return new JsonResponse($e->getMessage(), 400);
        }

        if($result instanceof PurchasedArtwork) {
            return new JsonResponse($result);
        }

        $message = ConstraintViolationListTransform::transfromArray($result);

        return new JsonResponse(
            $message,
            400,
        );
    }
}