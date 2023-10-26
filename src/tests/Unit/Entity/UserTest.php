<?php
namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(User::class)]
class UserTest extends TestCase
{
    public function testValidUserEntity(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword('password');

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($user);

        $this->assertCount(0, $violations);
    }

    public function testInvalidEmail(): void
    {
        $user = new User();
        $user->setEmail('invalid-email');
        $user->setPassword('password');

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($user);

        $this->assertCount(1, $violations);
        $this->assertEquals('This value is not a valid email address.', $violations[0]->getMessage());
    }

    public function testBlankEmail(): void
    {
        $user = new User();
        $user->setPassword('password');

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();

        $violations = $validator->validate($user);

        $this->assertCount(1, $violations);
    }
}
