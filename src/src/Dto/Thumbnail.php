<?php
namespace App\Dto;

class Thumbnail
{
    public ?string $lqip;
    public ?int $width;
    public ?int $height;
    public ?string $altText;

    /**
     * @param array<string, mixed> $item
     * @return Thumbnail
     */
    public static function createByArray(array $item): Thumbnail
    {
        $thumbnail = new Thumbnail();
        $thumbnail->lqip = $item['lqip'] ?? null;
        $thumbnail->width = $item['width'] ?? null;
        $thumbnail->height = $item['height'] ?? null;
        $thumbnail->altText = $item['alt_text'] ?? null;
        
        return $thumbnail;
    }
}