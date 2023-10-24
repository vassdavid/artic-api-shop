<?php

namespace App\Tests\Unit\Controller;

use PHPUnit\Framework\TestCase;
use App\Request\ListArtworkRequest;
use App\Controller\ArtworkController;
use App\Interfaces\ArtworkServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ArtworkController::class)]
#[CoversFunction("listAction")]
class ListActionTest extends TestCase
{
    public function testSuccessListAction(): void
    {
        // Create mock objects for ShowArtworkRequest and ArtworkServiceInterface
        $request = $this->createMock(ListArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);

        // Set up expectations for the service mock

        // Create an instance of ArtworkController
        $controller = new ArtworkController();

        // Call the showAction method with the mock objects
        $response = $controller->listAction($request, $service);

        // Perform assertions based on the expected behavior and results
        $this->assertInstanceOf(JsonResponse::class, $response);
        // Add more assertions as needed
    }

    public function testNotFoundListAction(): void
    {
        // Create mock objects for ShowArtworkRequest and ArtworkServiceInterface
        $request = $this->createMock(ListArtworkRequest::class);
        $service = $this->createMock(ArtworkServiceInterface::class);

        // Set up expectations for the service mock to throw NotFoundHttpException
        $service->method('listArtwork')
            ->willThrowException(new NotFoundHttpException('Artwork not found.'));

        // Create an instance of ArtworkController
        $controller = new ArtworkController();

        // Call the showAction method with the mock objects and expect a NotFoundHttpException
        $this->expectException(NotFoundHttpException::class);
        $controller->listAction($request, $service);
    }
}
