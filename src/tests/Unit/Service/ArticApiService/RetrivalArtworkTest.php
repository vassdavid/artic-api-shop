<?php
namespace App\Tests\Unit\Service\ArticApiService;

use App\Dto\Artwork;
use PHPUnit\Framework\TestCase;
use App\Service\ArticApiService;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArticApiServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Exception\InvalidApiResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ArticApiService::class)]
class RetrivalArtworkTest extends TestCase
{
    private const EXAMPLE_SINGLE_RESULT = '{"data":{"id":303,"title":"Panel Fragment","thumbnail":{"lqip":"data:image\/gif;base64,R0lGODlhAwAFAPMAAGdKOnZdSHtYSXFZUH5oVoBlT4RvXYt3Y4x0YIl2ZJB5Y5R+ZpSBaZWDbJSFbwAAACH5BAAAAAAALAAAAAADAAUAAAQLMKmGBFlgsFCcOREAOw==","width":3091,"height":4886,"alt_text":"A work made of wool (camelid) and cotton, plain weave of discontinuous single interlocking warps and wefts."},"artist_title":"Nasca"},"info":{"license_text":"The `description` field in this response is licensed under a Creative Commons Attribution 4.0 Generic License (CC-By) and the Terms and Conditions of artic.edu. All other data in this response is licensed under a Creative Commons Zero (CC0) 1.0 designation and the Terms and Conditions of artic.edu.","license_links":["https:\/\/creativecommons.org\/publicdomain\/zero\/1.0\/","https:\/\/www.artic.edu\/terms"],"version":"1.9"},"config":{"iiif_url":"https:\/\/www.artic.edu\/iiif\/2","website_url":"http:\/\/www.artic.edu"}}';
    private function createMockHttpClient(string $result, int $statusCode=200): HttpClientInterface
    {
        $client = $this->createMock(HttpClientInterface::class);
        $client->method('request')->willReturnCallback(function (string $url) use ($result, $statusCode) {
            $response = $this->createMock(ResponseInterface::class);
            $response->method('getStatusCode')->willReturn($statusCode);
            $response->method('toArray')->willReturn(json_decode($result, true));

            return $response;
        });

        return $client;
    }

    private function createMockServiceForSingleResult(): ArticApiServiceInterface
    {
        return new ArticApiService(
            $this->createMockHttpClient(self::EXAMPLE_SINGLE_RESULT),
        );
    }

    public function testSuccessRetrivalArtWork(): void
    {
        $service = $this->createMockServiceForSingleResult();
        $request = new ShowArtworkRequest();
        $request->id = 303;
        $result = $service->retrievalArtwork($request);

        $this->assertInstanceOf(Artwork::class, $result);
    }

    public function testWithInvalidStatusRetrivalArtWork(): void
    {
        $service = new ArticApiService(
            $this->createMockHttpClient("{}", 404),
        );
        $request = new ShowArtworkRequest();
        $request->id = 303;
        $this->expectException(NotFoundHttpException::class);
        $service->retrievalArtwork($request);
    }

    public function testInvalidFormatRetrivalArtWork(): void
    {
        $service = new ArticApiService(
            $this->createMockHttpClient("{}", 200),
        );
        $request = new ShowArtworkRequest();
        $request->id = 303;
        $this->expectException(InvalidApiResponseException::class);
        $service->retrievalArtwork($request);
    }
}