<?php
namespace App\Tests\Unit\Controller\ArtworkController\PurchasedArtworkController;

use App\Entity\User;
use ReflectionClass;
use PHPUnit\Framework\TestCase;
use App\Entity\PurchasedArtwork;
use App\Request\BuyArtworkRequest;
use App\Exception\AlreadyBuyedException;
use App\Service\PurchasedArtworkService;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\PurchasedArtworkController;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\PurchasedArtworkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

#[CoversClass(PurchasedArtworkController::class)]
class BuyActionTest extends TestCase
{
    private function createMockUser(): User
    {
        $user = $this->createMock(User::class);
        
        $a = new User();
        $reflection = new ReflectionClass($a);
        $reflectionProperty = $reflection->getProperty('id');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($a, 333);
        
        $user->method('getId')->willReturn(333);
        $user->method('getEmail')->willReturn('test@tester.hu');
        $user->method('getRoles')->willReturn(['ROLE_USER']);

        return $user;
    }

    private function createMockSecurity(): Security
    {
        $user = $this->createMockUser();
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        return $security;
    }

    public function testBuyArtworkActionWithValidRequest(): void
    {

        $service = $this->createMock(PurchasedArtworkServiceInterface::class);

        $request = new BuyArtworkRequest();
        $request->artworkId = 123;

        $service->expects($this->once())
            ->method('buyArtwork')
            ->willReturn(new PurchasedArtwork());

        $controller =  new PurchasedArtworkController();
        $security = $this->createMockSecurity();

        $response = $controller->buyArtworkAction($request, $service, $security);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testBuyArtworkActionWithInvalidRequest(): void
    {

        $user = $this->createMockUser();
    
        $service = $this->createMock(PurchasedArtworkServiceInterface::class);
        $violationList = $this->createMock(ConstraintViolationListInterface::class);
        $service->expects($this->once())
            ->method('buyArtwork')
            ->willReturn($violationList);
   
        $request = new BuyArtworkRequest();
        $request->artworkId = 99999;

        $controller = new PurchasedArtworkController();
        $security = $this->createMockSecurity();

        $result = $controller->buyArtworkAction($request, $service, $security);

        $this->assertEquals($result->getStatusCode(), 400);
    }

    public function testBuyArtworkActionWithAlreadyPurchasedArtwork(): void
    {
        $service = $this->createMock(PurchasedArtworkServiceInterface::class);
        $service->expects($this->once())
            ->method('buyArtwork')
            ->willThrowException(new AlreadyBuyedException());
        

        $request = new BuyArtworkRequest();
        $request->artworkId = 123;
        $security = $this->createMockSecurity();

        $controller = new PurchasedArtworkController();

        $result = $controller->buyArtworkAction($request, $service, $security);

        $this->assertEquals($result->getStatusCode(), 400);
    }
    
}
