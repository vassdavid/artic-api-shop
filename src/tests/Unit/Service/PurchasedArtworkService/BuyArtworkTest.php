<?php
namespace App\Tests\Unit\Service\PurchasedArtworkService;

use App\Dto\Artwork;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use App\Service\PurchasedArtworkService;
use Doctrine\ORM\EntityManagerInterface;
use App\Interfaces\ArticApiServiceInterface;
use App\Repository\PurchasedArtworkRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[CoversClass(PurchasedArtworkService::class)]
class BuyArtworkTest extends TestCase
{   
    public function testBuyArtworkWithValidRequest(): void
    {
        // Arrange
        $user = new User();
        $request = new BuyArtworkRequest();
        $request->artworkId = 123;
        
        // Mocking
        $repository = $this->createMock(PurchasedArtworkRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $articApiService = $this->createMock(ArticApiServiceInterface::class);
        $repository->method('findOneBy')->willReturn(null);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        $articApiService->method('retrievalArtwork')->willReturn(new Artwork());
        
        $service = new PurchasedArtworkService($repository, $entityManager, $validator, $articApiService);
        $result = $service->buyArtwork($request, $user);

        $this->assertInstanceOf(PurchasedArtwork::class, $result);
    }

    public function testBuyArtworkWithInvalidArtworkId(): void
    {
        // Arrange
        $user = new User();
        $request = new BuyArtworkRequest();
        $request->artworkId = 123;

        // Mocking
        $repository = $this->createMock(PurchasedArtworkRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $articApiService = $this->createMock(ArticApiServiceInterface::class);
        $repository->method('findOneBy')->willReturn(null);
        $validator->method('validate')->willReturn(new ConstraintViolationList());
        $articApiService->method('retrievalArtwork')->willThrowException(new NotFoundHttpException());
        
        $service = new PurchasedArtworkService($repository, $entityManager, $validator, $articApiService);
        $result = $service->buyArtwork($request, $user);

        $this->assertInstanceOf(ConstraintViolationListInterface::class, $result);
    }

    public function testBuyArtworkWithExistingPurchase(): void
    {
        // Arrange
        $user = new User();
        $request = new BuyArtworkRequest();
        $request->artworkId = 123;

        // Mocking
        $repository = $this->createMock(PurchasedArtworkRepository::class);
        $repository->method('findOneBy')->willReturn(new PurchasedArtwork());
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        $articApiService = $this->createMock(ArticApiServiceInterface::class);

        $service = new PurchasedArtworkService($repository, $entityManager, $validator, $articApiService);

        // Act and Assert
        $this->expectException(AlreadyBuyedException::class);
        $service->buyArtwork($request, $user);
    }

}