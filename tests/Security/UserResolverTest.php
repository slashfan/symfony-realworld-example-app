<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Entity\User;
use App\Security\UserResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserResolverTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testGetCurrentUserWithNullUser(): void
    {
        $this->expectException(\Exception::class);

        $storage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $storage->method('getToken')->willReturn(null);

        /** @var TokenStorageInterface $storage */
        $resolver = new UserResolver($storage);
        $resolver->getCurrentUser();
    }

    /**
     * @throws \Exception
     */
    public function testGetCurrentUserWithNonAppUser(): void
    {
        $this->expectException(\Exception::class);

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
        $user = $this->getMockBuilder(User::class)->getMock();

        $token = $this->getMockBuilder(TokenInterface::class)->getMock();
        $token->method('getUser')->willReturn($user);

        $storage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $storage->method('getToken')->willReturn($token);

        /** @var TokenStorageInterface $storage */
        $resolver = new UserResolver($storage);
        $this->assertSame($user, $resolver->getCurrentUser());
    }
}
