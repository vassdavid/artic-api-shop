<?php
namespace App\Tests\Unit\Service\ArticApiService;

use App\Dto\Artwork;
use PHPUnit\Framework\TestCase;
use App\Service\ArticApiService;
use App\Request\ListArtworkRequest;
use App\Response\Artic\ArticListResponse;
use App\Interfaces\ArticApiServiceInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Exception\InvalidApiResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(ArticApiService::class)]
class RetrivalArtworkListTest extends TestCase
{
    private const EXAMPLE_LIST_RESULT = '{"pagination":{"total":123203,"limit":12,"offset":0,"total_pages":10267,"current_page":1,"next_url":"https:\/\/api.artic.edu\/api\/v1\/artworks?page=2&fields=id%2Ctitle%2Cartist_title%2Cthumnail"},"data":[{"id":7122,"title":"Seated Boy","artist_title":"Max Beckmann"},{"id":6010,"title":"Number 19","artist_title":"Mark Rothko"},{"id":9505,"title":"Two Studies of a Roma Woman and a Roma Boy in a Large Hat","artist_title":"Jacob de Gheyn, II"},{"id":13096,"title":"The Baptism of the Eunuch","artist_title":"Rembrandt van Rijn"},{"id":11723,"title":"Woman at Her Toilette","artist_title":"Berthe Morisot"},{"id":14572,"title":"The Millinery Shop","artist_title":"Hilaire Germain Edgar Degas"},{"id":23700,"title":"The Praying Jew","artist_title":"Marc Chagall"},{"id":21977,"title":"Melon-Shaped Ewer with Stylized Flowers","artist_title":null},{"id":25699,"title":"Birmingham Race Riot, from X + X (Ten Works by Ten Painters)","artist_title":"Andy Warhol"},{"id":26715,"title":"Seated Nude","artist_title":"\u00c9douard Manet"},{"id":25809,"title":"Maquette for Richard J. Daley Center Sculpture","artist_title":"Pablo Picasso"},{"id":27281,"title":"Madam Pompadour","artist_title":"Amedeo Modigliani"}],"info":{"license_text":"The `description` field in this response is licensed under a Creative Commons Attribution 4.0 Generic License (CC-By) and the Terms and Conditions of artic.edu. All other data in this response is licensed under a Creative Commons Zero (CC0) 1.0 designation and the Terms and Conditions of artic.edu.","license_links":["https:\/\/creativecommons.org\/publicdomain\/zero\/1.0\/","https:\/\/www.artic.edu\/terms"],"version":"1.9"},"config":{"iiif_url":"https:\/\/www.artic.edu\/iiif\/2","website_url":"http:\/\/www.artic.edu"}}';

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

    private function createMockServiceForListResult(): ArticApiServiceInterface
    {
        return new ArticApiService(
            $this->createMockHttpClient(self::EXAMPLE_LIST_RESULT),
        );
    }

    public function testSuccessRetrivalArtworkList(): void
    {
        $service = $this->createMockServiceForListResult();
        $request = new ListArtworkRequest();
        $result = $service->retrivalArtworkList($request);

        $this->assertInstanceOf(ArticListResponse::class, $result);
        $this->assertCount(12, $result->data);
        $this->assertInstanceOf(Artwork::class, $result->data[0]);
    }

    public function testNullRetrivalArtWorkList(): void
    {
        $service = new ArticApiService(
            $this->createMockHttpClient('{"pagination":{"total":123203,"limit":12,"offset":0,"total_pages":10267,"current_page":1},"data":[]}'),
        );
        $request = new ListArtworkRequest();
        $result = $service->retrivalArtworkList($request);

        $this->assertInstanceOf(ArticListResponse::class, $result);
        $this->assertCount(0, $result->data);
    }

    public function testInvalidStatusRetrivalArtWorkList(): void
    {
        $service = new ArticApiService(
            $this->createMockHttpClient('{"data":[]}', 404),
        );
        $request = new ListArtworkRequest();

        $this->expectException(NotFoundHttpException::class);
        $service->retrivalArtworkList($request);

    }

    public function testInvalidFormatRetrivalArtWorkList(): void
    {
        $service = new ArticApiService(
            $this->createMockHttpClient('{"no-data":[1,1]}'),
        );
        $request = new ListArtworkRequest();

        $this->expectException(InvalidApiResponseException::class);
        $service->retrivalArtworkList($request);
    }
}