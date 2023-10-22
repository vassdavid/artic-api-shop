<?php
namespace App\Tests\Integration\Service;

use App\Dto\Artwork;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArtworkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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
}