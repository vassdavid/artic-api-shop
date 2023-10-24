<?php
namespace App\Tests\Unit\Service\ArtworkService;

use App\Dto\Artwork;
use App\Interfaces\ArtworkServiceInterface;
use App\Service\ArtworkService;
use PHPUnit\Framework\TestCase;
use App\Service\ArticApiService;
use Symfony\Contracts\Cache\CacheInterface;
use App\Interfaces\ArticApiServiceInterface;
use App\Request\ShowArtworkRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[CoversClass(ArtworkService::class)]
#[CoversFunction("showArtwork")]
class ShowArtworkTest extends TestCase
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
        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->method('get')
            ->willReturn($artwork);

        return $cache;
    }

    public function testShowArtwork(): void
    {
        $service = $this->createArtworkServiceWithMockedCache();

        $request = new ShowArtworkRequest();
        $request->id = 707;
        $artwork = $service->showArtwork($request);

        $this->assertInstanceOf(Artwork::class, $artwork);
    }
}