<?php
namespace App\Service;

use App\Dto\Artwork;
use App\Request\ListArtworkRequest;
use App\Request\ShowArtworkRequest;
use Symfony\Contracts\Cache\ItemInterface;
use App\Interfaces\ArtworkServiceInterface;
use Symfony\Contracts\Cache\CacheInterface;
use App\Interfaces\ArticApiServiceInterface;

class ArtworkService implements ArtworkServiceInterface
{
    /** Cache Expire Time */
    private const CACHE_EXPIRES = 1800;
    private const ITEM_CACHE_KEY_PREFIX = 'Artwork.';
    private const LIST_CACHE_KEY_PREFIX = 'ArtworkList.';
 
    public function __construct(
        private ArticApiServiceInterface $apiService,
        private CacheInterface $cache,
    ) { }

    private function buildListCacheKey(ListArtworkRequest $request): string
    {
        return 
            self::LIST_CACHE_KEY_PREFIX 
            . '.' . (string)$request->limit
            . '.' . (string)$request->page
        ;
    }
    private function buildItemCacheKey(ShowArtworkRequest $request): string
    {
        return self::ITEM_CACHE_KEY_PREFIX . (string)$request->id;
    }

    private function getItemCache(ShowArtworkRequest $request): ?Artwork
    {
        return $this->cache->get(
            $this->buildItemCacheKey($request),
            function(ItemInterface $item) use ($request): ?Artwork
            {
                $item->expiresAfter(self::CACHE_EXPIRES);

                return $this->apiService->retrievalArtwork($request);
            }
        );
    }

    /**
     * Get Artwork List cache or load List
     *
     * @param ListArtworkRequest $request
     * @return Artwork[]
     */
    private function getListCache(ListArtworkRequest $request): array
    {
        return $this->cache->get(
            $this->buildListCacheKey($request),
            function(ItemInterface $item) use ($request): array
            {
                $item->expiresAfter(self::CACHE_EXPIRES);

                return $this->apiService->retrivalArtworkList($request);
            }
        );
    }

    public function showArtwork(ShowArtworkRequest $request): ?Artwork
    {
        return $this->getItemCache($request);
    }


    /**
     * List array of artwork based in request
     *
     * @param ListArtworkRequest $request
     * @return Artwork[]
     */
    public function listArtwork(ListArtworkRequest $request): array
    {
        return $this->getListCache($request);
    }

}