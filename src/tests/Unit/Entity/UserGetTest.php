<?php
namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

#[CoversClass(User::class)]
class UserGetTest extends TestCase
{
    public function testGetId(): void
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');

        $this->assertEquals('user@example.com', $user->getUserIdentifier());
    }

    public function testGetRoles(): void
    {
        $user = new User();

        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testGetEmail(): void
    {
        $user = new User();
        $user->setEmail('user@example.com');

        $this->assertEquals('user@example.com', $user->getEmail());
    }

    public function testGetPassword(): void
    {
        $user = new User();
        $user->setPassword('hashed_password');

        $this->assertEquals('hashed_password', $user->getPassword());
    }
}