<?php
namespace App\Tests\Unit\Controller\ArtworkController\PurchasedArtworkController;

use App\Controller\PurchasedArtworkController;
use App\Entity\User;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use App\Service\PurchasedArtworkService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\PurchasedArtworkServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[CoversClass(PurchasedArtworkController::class)]
class BuyActionTest extends WebTestCase
{
    public function testBuyArtworkActionWithValidRequest(): void
    {
        $client = static::createClient();
        $user = new User();
        $client->loginUser($user);

        $container = static::getContainer();
        $service = $this->createMock(PurchasedArtworkServiceInterface::class);
        $container->set(PurchasedArtworkService::class, $service);

        $request = new BuyArtworkRequest();
        $request->artworkId = 123;

        $service->expects($this->once())
            ->method('buyArtwork')
            ->willReturn(new PurchasedArtwork());

        $client->request(
            Request::METHOD_POST,
            '/api/artwork/buy',
            content: json_encode($request),
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseIsSuccessful();
    }

    public function testBuyArtworkActionWithInvalidRequest(): void
    {
        $client = static::createClient();
        $user = new User();
        $client->loginUser($user);

        $container = static::getContainer();
        
        /** Mocking Service */
        $service = $this->createMock(PurchasedArtworkServiceInterface::class);
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $service->expects($this->once())
            ->method('buyArtwork')
            ->willReturn($violationList);
        
        /** Set mock service to container */
        $container->set(PurchasedArtworkServiceInterface::class, $service);
        
        /** Create request for HTTP */
        $request = new BuyArtworkRequest();
        $request->artworkId = 99999;
        

        $client->request(
            Request::METHOD_POST,
            '/api/artwork/buy',
            content: json_encode($request),
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testBuyArtworkActionWithAlreadyPurchasedArtwork(): void
    {
        $client = static::createClient();
        $user = new User();
        $client->loginUser($user);

        $service = $this->createMock(PurchasedArtworkServiceInterface::class);
        $service->expects($this->once())
            ->method('buyArtwork')
            ->willThrowException(new AlreadyBuyedException());
        
        $container = static::getContainer();
        $container->set(PurchasedArtworkServiceInterface::class, $service);

        $request = new BuyArtworkRequest();
        $request->artworkId = 123;


        $client->request(
            Request::METHOD_POST,
            '/api/artwork/buy',
            content: json_encode($request),
            server: ['CONTENT_TYPE' => 'application/json']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }
    
}
