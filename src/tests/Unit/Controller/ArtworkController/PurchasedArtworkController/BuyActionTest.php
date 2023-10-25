<?php
namespace App\Tests\Unit\Controller\ArtworkController\PurchasedArtworkController;


use App\Entity\User;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use App\Service\PurchasedArtworkService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Interfaces\PurchasedArtworkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BuyActionTest extends WebTestCase
{
    public function testBuyArtworkActionWithValidRequest()
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

}
