<?php
namespace App\Service;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use App\Response\Artic\ArticListResponse;
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
    private const SINGLE_REQUIRED_FIELD = [
        'id',
        'title',
        'artist_title',
    ];

    private const PAGINATION_REQUIRED_FIELD = [
        'total',
        'total',
        'limit',
        'offset',
        'total_pages',
        'current_page',
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
     * Get non existent array keys if exist 
     *
     * @param array<string,mixed> $array
     * @param string[] $keyList
     * @return string[]
     */
    private function getMissigArrayKeys(array $array, array $keyList): array
    {
        $missingFields = [];
        foreach($keyList as $fieldName) {
            if(!array_key_exists($fieldName, $array)) {
                $missingFields[] = $fieldName;
            }
        }

        return $missingFields;
    }


    /**
     * Check required Artwork field is setted
     * 
     * @param array<string,mixed> $artwork
     * @throws InvalidApiResponseException
     * @return void
     */
    private function checkRequiredArtworkFields(array $artwork): void
    {
        $missingFields = $this->getMissigArrayKeys($artwork, self::SINGLE_REQUIRED_FIELD);

        if(count($missingFields) > 0) {
            throw new InvalidApiResponseException("Missing Artwork response fields: (" . join(',',$missingFields) . ")");
        }
    }

    /**
     * Check required List field is setted
     * 
     * @param array<string,mixed> $list
     * @throws InvalidApiResponseException
     * @return void
     */
    private function checkRequiredPaginationFields(array $list): void
    {
        $missingFields = $this->getMissigArrayKeys($list, self::PAGINATION_REQUIRED_FIELD);

        if(count($missingFields) > 0) {
            throw new InvalidApiResponseException("Missing Pagination response fields: (" . join(',',$missingFields) . ")");
        }
    }

    private function buildFieldQueryPart(): string
    {
        return 'fields=' . implode(',', self::FIELD_LIST);
    }

    /**
     * Retrival single artwork
     *
     * @param ShowArtworkRequest $request
     * @throws InvalidApiResponseException
     * @throws NotFoundHttpException
     * @throws HttpException
     * @return Artwork
     */
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
            throw new InvalidApiResponseException('Missing "data" field.');
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
     * @throws InvalidApiResponseException
     * @throws NotFoundHttpException
     * @throws HttpException
     * @return ArticListResponse
     */
    public function retrivalArtworkList(ListArtworkRequest $request): ArticListResponse
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

        //validate response format
        if( !isset($content['data'])  ) {
            //@todo log error
            throw new InvalidApiResponseException('Missing "data" field in a Response.');
        }
        //validate response format
        if( !isset($content['pagination'])  ) {
            //@todo log error
            throw new InvalidApiResponseException('Missing "pagination" field in a Response.');
        }


        $this->checkRequiredPaginationFields($content['pagination']);
        
        $list = [];
        //Process response
        foreach( $content['data'] as $item ) {
            $this->checkRequiredArtworkFields( $item );
            $list[] = Artwork::createByArray( $item );
        }

        //build response object
        $listResponse = new ArticListResponse();
        $listResponse->total = $content['pagination']['total'];
        $listResponse->limit = $content['pagination']['limit'];
        $listResponse->offset = $content['pagination']['offset'];
        $listResponse->totalPages = $content['pagination']['total_pages'];
        $listResponse->currentPage = $content['pagination']['current_page'];
        $listResponse->data = $list;
        
        return $listResponse;
    }
}