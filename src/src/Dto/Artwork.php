<?php
namespace App\Dto;

use App\Dto\Thumbnail;

class Artwork
{
    public ?int  $id;
    public ?string $title;
    public ?string $artistTitle;
    public ?Thumbnail $thumbnail;

    /**
     * This function create Artwork DTO instance from array
     *
     * @param array<string, mixed> $item - one array item of artwork
     * @return Artwork
     */
    public static function createByArray(array $item): Artwork
    {
        $artwork = new Artwork();
        $artwork->id = $item['id'];
        $artwork->title = $item['title'];
        $artwork->artistTitle = $item['artist_title'];

        if(isset($item['thumbnail'])) {
            $artwork->thumbnail = Thumbnail::createByArray($item['thumbnail']);
        }

        return $artwork;
    }
}