<?php
namespace App\Service;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Interfaces\ArticApiServiceInterface;
use App\Exception\InvalidApiResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    private const REQUIRED_FIELD_LIST = [
        'id',
        'title',
        'artist_title',
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
    ) { }

    /**
     * Check response http code is valid
     *
     * @param ResponseInterface $response
     * @throws NotFoundHttpException - if response code 404
     * @throws HttpException - else if not 200 than 500 error
     * @return void
     */
    private function handleHttpStatusCodeExceptions(ResponseInterface $response): void
    {
        //@todo Check other statuscode
        if( $response->getStatusCode() == 404) {
            throw new NotFoundHttpException();
        } else if( $response->getStatusCode()  !== 200) {
            //@todo move translate
            //@todo log error by logger
            throw new HttpException(500, 'Internal Server Error');
        }

    }

    /**
     * Check required field is setted
     * 
     * @param array<string,mixed> $artwork
     * @throws InvalidApiResponseException
     * @return void
     */
    private function checkRequiredArtworkFields(array $artwork): void
    {
        $missingFields = [];
        foreach(self::REQUIRED_FIELD_LIST as $fieldName) {
            if(!array_key_exists($fieldName, $artwork)) {
                $missingFields[] = $fieldName;
            }
        }

        if(count($missingFields) > 0) {
            throw new InvalidApiResponseException();
        }
    }

    private function buildFieldQueryPart(): string
    {
        return 'fields=' . implode(',', self::FIELD_LIST);
    }

    public function retrievalArtwork(ShowArtworkRequest $request): Artwork
    {
        $response  = $this->httpClient->request(
            'GET', 
            self::GET_API_URL . '/' . $request->id . '?'
            . $this->buildFieldQueryPart()
        );

        //throw http exceptions
        $this->handleHttpStatusCodeExceptions($response);
        
        // casts the response JSON content to a PHP array
        $content = $response->toArray();

        //check response format
        if( !isset($content['data'])  ) {
            //@todo log error
            throw new InvalidApiResponseException();
        }
        $this->checkRequiredArtworkFields($content['data']);

        //Processing data
        $artwork = Artwork::createByArray( $content['data'] );

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

        //throw http exceptions
        $this->handleHttpStatusCodeExceptions($response);

        $content = $response->toArray();

        if( !isset( $content['data'] ) ) {
            //@todo log error
            throw new InvalidApiResponseException();
        }
        
        $list = [];
        //Process array
        foreach( $content['data'] as $item ) {
            $this->checkRequiredArtworkFields( $item );
            $list[] = Artwork::createByArray( $item );
        }

        return $list;
    }
}