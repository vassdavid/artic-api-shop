<?php
namespace App\Tests\Unit\Entity;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\PurchasedArtwork;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(PurchasedArtwork::class)]
class PurchasedArtworkGetTest extends TestCase
{
    public function testGetId()
    {
        $purchasedArtwork = new PurchasedArtwork();

        $this->assertNull($purchasedArtwork->getId());
    }

    public function testGetUser()
    {
        $purchasedArtwork = new PurchasedArtwork();
        $this->assertNull($purchasedArtwork->getUser());

        $user = new User();
        $purchasedArtwork->setUser($user);
        $this->assertSame($user, $purchasedArtwork->getUser());
    }

    public function testGetArtworkId()
    {
        $purchasedArtwork = new PurchasedArtwork();
        $this->assertNull($purchasedArtwork->getArtworkId());

        $artworkId = 123;
        $purchasedArtwork->setArtworkId($artworkId);
        $this->assertSame($artworkId, $purchasedArtwork->getArtworkId());
    }
}