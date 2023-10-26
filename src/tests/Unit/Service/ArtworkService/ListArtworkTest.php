<?php
namespace App\Tests\Unit\Service\ArtworkService;

use App\Dto\Artwork;
use App\Service\ArtworkService;
use PHPUnit\Framework\TestCase;
use App\Service\ArticApiService;
use App\Request\ListArtworkRequest;
use App\Response\Artic\ArticListResponse;
use App\Interfaces\ArtworkServiceInterface;
use Symfony\Contracts\Cache\CacheInterface;
use App\Interfaces\ArticApiServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[CoversClass(ArtworkService::class)]
class ListArtworkTest extends TestCase
{
    private function getExampleArtwork(): Artwork
    {
        $artwork = new Artwork();
        $artwork->artistTitle = 'Example Artist';
        $artwork->id = 777;
        $artwork->title = 'Example Title';

        return $artwork;
    }

    private function createArtworkServiceWithMockedCache(): ArtworkServiceInterface
    {
        $service = new ArtworkService(
            $this->createMockedApiService(),
            $this->createFilledMockCache(),
        );

        return $service;
    }


    private function createMockedApiService(): ArticApiServiceInterface
    {
        $service = new ArticApiService(
            $this->createMock(HttpClientInterface::class),
    
        );
    
        return $service;
    }

    private function createFilledMockCache(): CacheInterface
    {
        $artwork = $this->getExampleArtwork();
        $response = new ArticListResponse();
        $response->data = [$artwork];
        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->method('get')
            ->willReturn($response);

        return $cache;
    }

    public function testSuccesfullyRetriveArtworkList(): void
    {
        $service = $this->createArtworkServiceWithMockedCache();

        $request = new ListArtworkRequest();
        $result = $service->listArtwork($request);

        $this->assertInstanceof(ArticListResponse::class, $result);
    }

}