<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Security\UserResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserResolverTest.
 */
class UserResolverTest extends TestCase
{
    /**
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testGetCurrentUserWithNullUser(): void
    {
        $storage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $storage->method('getToken')->willReturn(null);

        /** @var TokenStorageInterface $storage */
        $resolver = new UserResolver($storage);
        $resolver->getCurrentUser();
    }

    /**
     * @throws \Exception
     *
     * @expectedException \Exception
     */
    public function testGetCurrentUserWithNonAppUser(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();

        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->method('getUser')->willReturn($user);

        $storage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $storage->method('getToken')->willReturn($token);

        /** @var TokenStorageInterface $storage */
        $resolver = new UserResolver($storage);
        $resolver->getCurrentUser();
    }

    /**
     * @throws \Exception
     */
    public function testGetCurrentUserWithAppUser(): void
    {
        $user = $this->getMockBuilder(\App\Entity\User::class)->getMock();

        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->method('getUser')->willReturn($user);

        $storage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $storage->method('getToken')->willReturn($token);

        /** @var TokenStorageInterface $storage */
        $resolver = new UserResolver($storage);
        $this->assertSame($user, $resolver->getCurrentUser());
    }
}
