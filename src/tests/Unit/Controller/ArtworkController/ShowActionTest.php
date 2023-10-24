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
        // Create mock objects for ShowArtworkRequest and ArtworkServiceInterface
        $request = $this->createMock(ShowArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);

        // Set up expectations for the service mock

        // Create an instance of ArtworkController
        $controller = new ArtworkController();

        // Call the showAction method with the mock objects
        $response = $controller->showAction($request, $service);

        // Perform assertions based on the expected behavior and results
        $this->assertInstanceOf(JsonResponse::class, $response);
        // Add more assertions as needed
    }

    public function testNotFoundShowAction(): void
    {
        // Create mock objects for ShowArtworkRequest and ArtworkServiceInterface
        $request = $this->createMock(ShowArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);

        // Set up expectations for the service mock to throw NotFoundHttpException
        $service->method('showArtwork')
            ->willThrowException(new NotFoundHttpException('Artwork not found.'));

        // Create an instance of ArtworkController
        $controller = new ArtworkController();

        // Call the showAction method with the mock objects and expect a NotFoundHttpException
        $this->expectException(NotFoundHttpException::class);
        $controller->showAction($request, $service);
    }
}
