<?php
namespace App\Tests\Integration\Service;

use App\Dto\Artwork;
use App\Service\ArtworkService;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArtworkServiceInterface;
use App\Request\ListArtworkRequest;
use App\Tests\Unit\Service\ArtworkService\ListArtworkTest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
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

        $this->assertIsArray($result);
        $this->assertTrue(count($result) > 0);
        $this->assertInstanceOf(Artwork::class, $result[0]);
    }
}