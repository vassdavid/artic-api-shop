<?php
namespace App\Interfaces;

use App\Entity\User;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface PurchasedArtworkServiceInterface
{
    /**
     * Register Artwork buys
     *
     * @param BuyArtworkRequest $request
     * @param User $user
     * @throws AlreadyBuyedException
     * @return ConstraintViolationListInterface|PurchasedArtwork - return type entity if modify is succes 
     */
    public function buyArtwork(BuyArtworkRequest $request, User $user): ConstraintViolationListInterface|PurchasedArtwork;
}