<?php
namespace App\Tests\Integration\Service;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArticApiServiceInterface;
use App\Service\ArticApiService;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ArticApiService::class)]
class ArticApiServiceTest extends KernelTestCase
{
    private function getService(): ArticApiServiceInterface
    {
        return self::getContainer()->get(ArticApiServiceInterface::class);
    }
    public function testSuccessfullyShowArtwork(): void
    {
        $service = $this->getService();
        $request = new ShowArtworkRequest();
        $request->id = 22;
        $result = $service->retrievalArtwork($request);
        $this->assertInstanceOf(Artwork::class, $result);
        $this->assertEquals($request->id, $result->id);
    }

    public function testSuccessfullyListArtworkArtwork(): void
    {
        $service = $this->getService();
        $request = new ListArtworkRequest();
        $result = $service->retrivalArtworkList($request);
        $this->assertIsArray($result);
        $this->assertTrue(count($result) > 0);
        $this->assertInstanceOf(Artwork::class, $result[0]);
    }

    public function testInvalidIdShowArtwork(): void
    {
        $service = $this->getService();
        $request = new ShowArtworkRequest();
        $request->id = 999999999;
        
        $this->expectException(NotFoundHttpException::class);
        $service->retrievalArtwork($request);        
    }
}