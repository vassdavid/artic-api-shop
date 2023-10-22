<?php
namespace App\Service;

use App\Dto\Artwork;
use App\Interfaces\ArticApiServiceInterface;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticApiService implements ArticApiServiceInterface
{
    private const BASE_API_URL = 'https://api.artic.edu/api/v1';
    private const GET_API_URL = self::BASE_API_URL . '/artworks';

    /**
     * List of showed field
     * ID, title, author and thumbnail
     */
    private const FIELD_LIST = [
        'id',
        'title',
        'artist_title',
        'thumbnail'
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
    ) { }

    private function buildFieldQueryPart(): string
    {
        return 'fields=' . implode(',', self::FIELD_LIST);
    }

    public function retrievalArtwork(ShowArtworkRequest $request): ?Artwork
    {
        $response  = $this->httpClient->request(
            'GET', 
            self::GET_API_URL . '/' . $request->id . '?'
            . $this->buildFieldQueryPart()
        );

        //@todo Check other statuscode
        if( $response->getStatusCode() !== 200) {
            throw new NotFoundHttpException();
        }

        // casts the response JSON content to a PHP array
        $content = $response->toArray();

        //Processing data
        $artwork = null;
        if( isset( $content['data'] ) ) {
            $artwork = Artwork::createByArray( $content['data'] );
        }

        return $artwork;
    }

    /**
     * List array of artwork based in request
     *
     * @param ListArtworkRequest $request
     * @return Artwork[]
     */
    public function retrivalArtworkList(ListArtworkRequest $request): array
    {
        $response = $this->httpClient->request(
            'GET',
            self::GET_API_URL . '?' 
            . 'page=' . $request->page
            . '&limit=' . $request->limit 
            . '&' . $this->buildFieldQueryPart()
        );

        if( $response->getStatusCode() !== 200) {
            throw new NotFoundHttpException();
        }

        $content = $response->toArray();

        //Process array
        $list = [];
        if( isset( $content['data'] ) ) {
            foreach( $content['data'] as $item ) {
                $list[] = Artwork::createByArray( $item );
            }
        }

        return $list;
    }
}