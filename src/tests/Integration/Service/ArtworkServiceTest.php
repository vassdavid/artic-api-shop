<?php
namespace App\Tests\Integration\Service;

use App\Dto\Artwork;
use App\Service\ArtworkService;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Response\Artic\ArticListResponse;
use App\Interfaces\ArtworkServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(ArtworkService::class)]
class ArtworkServiceTest extends KernelTestCase
{
    private function getService(): ArtworkServiceInterface
    {
        return self::getContainer()->get(ArtworkServiceInterface::class);
    }
    
    public function testShowArtwork(): void
    {
        $service = $this->getService();
        $request = new ShowArtworkRequest();
        $request->id = 33;
        $result =$service->showArtwork($request);

        $this->assertInstanceOf(Artwork::class, $result);
        $this->assertEquals($request->id, $result->id);
    }
    public function testListArtwork(): void
    {
        $service = $this->getService();
        $request = new ListArtworkRequest();
        $result =$service->listArtwork($request);

        $this->assertInstanceOf(ArticListResponse::class, $result);
        $this->assertTrue(count($result->data) > 0);
        $this->assertInstanceOf(Artwork::class, $result->data[0]);
    }
}