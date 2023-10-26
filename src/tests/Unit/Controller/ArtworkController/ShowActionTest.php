<?php

namespace App\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Request\ShowArtworkRequest;
use App\Controller\ArtworkController;
use App\Interfaces\ArtworkServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ArtworkController::class)]
class ShowActionTest extends TestCase
{
    public function testSuccessShowAction(): void
    {

        $request = $this->createMock(ShowArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);
        $controller = new ArtworkController();

        $response = $controller->showAction($request, $service);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testNotFoundShowAction(): void
    {

        $request = $this->createMock(ShowArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);

        $service->method('showArtwork')
            ->willThrowException(new NotFoundHttpException('Artwork not found.'));

        $controller = new ArtworkController();
        
        $this->expectException(NotFoundHttpException::class);
        $controller->showAction($request, $service);
    }
}
