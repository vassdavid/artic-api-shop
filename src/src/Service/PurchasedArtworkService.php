<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\PurchasedArtwork;
use Doctrine\ORM\Mapping\Entity;
use App\Request\BuyArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Exception\AlreadyBuyedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\ArticApiServiceInterface;
use App\Repository\PurchasedArtworkRepository;
use App\Interfaces\PurchasedArtworkServiceInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PurchasedArtworkService implements PurchasedArtworkServiceInterface
{
    public function __construct(
        private PurchasedArtworkRepository $purchasedArtworkRepository,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private ArticApiServiceInterface $articApiService,
    ) { }


    private function checkArtworkExits(int $artworkId, ConstraintViolationListInterface &$errors): void
    {
        if ($artworkId !== null) {
            $request = new ShowArtworkRequest();
            $request->id = $artworkId;
            try {
                $this->articApiService->retrievalArtwork($request);
            } catch(NotFoundHttpException $e) {
                $errors->add(new ConstraintViolation(
                    'The given artwork isn\'t exists!',
                    'The value "{{ value }}" is invalid.',
                    ['{{ param }}' => $artworkId],
                    null,
                    'validators',
                    $artworkId,
                ));
            }
        }
    }

    /**
     * Register Artwork buys
     *
     * @param BuyArtworkRequest $request
     * @param User $user
     * @throws AlreadyBuyedException
     * @return ConstraintViolationListInterface|PurchasedArtwork - return type entity if modify is succes 
     */
    public function buyArtwork(BuyArtworkRequest $request, User $user): ConstraintViolationListInterface|PurchasedArtwork
    {
        //check PurchasedArtworkis exists
        $existedPurchase = $this->purchasedArtworkRepository->findOneBy([
            'artworkId' => $request->artworkId,
        ]);

        if ($existedPurchase instanceof PurchasedArtwork) {
            throw new AlreadyBuyedException();
        }

        $purchasedArtwork = new PurchasedArtwork();
        $purchasedArtwork->setUser($user);
        $purchasedArtwork->setArtworkId($request->artworkId);

        $errors = $this->validator->validate($purchasedArtwork);
        
        $this->checkArtworkExits($request->artworkId, $errors);

        if (count($errors) > 0) {
            return $errors;
        }

        $this->entityManager->persist($purchasedArtwork);
        $this->entityManager->flush();

        return $purchasedArtwork;
    }
}