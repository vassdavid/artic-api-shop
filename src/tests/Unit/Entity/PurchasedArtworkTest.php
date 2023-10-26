<?php
namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\PurchasedArtwork;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Validator\Validation;

#[CoversClass(PurchasedArtwork::class)]
class PurchasedArtworkTest extends TestCase
{ 
    public function testValidPurchasedArtworkEntity(): void
    {
        $user = new User();

        $purchasedArtwork = new PurchasedArtwork();
        $purchasedArtwork->setUser($user);
        $purchasedArtwork->setArtworkId(123);
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $violations = $validator->validate($purchasedArtwork);

        $this->assertCount(0, $violations);
    }

    public function testBlankUser(): void
    {
        $purchasedArtwork = new PurchasedArtwork();
        $purchasedArtwork->setArtworkId(123);

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

        $violations = $validator->validate($purchasedArtwork);

        $this->assertCount(1, $violations);
        $this->assertEquals('This value should not be blank.', $violations[0]->getMessage());
    }

    public function testBlankArtworkId(): void
    {
        $user = new User();

        $purchasedArtwork = new PurchasedArtwork();
        $purchasedArtwork->setUser($user);

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();


        $violations = $validator->validate($purchasedArtwork);
        $this->assertCount(1, $violations);
        $this->assertEquals('This value should not be blank.', $violations[0]->getMessage());
    }
}
