<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Processor;

use App\DataFixtures\Processor\UserProcessor;
use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

final class UserProcessorTest extends TestCase
{
    public function testPreProcessWithArticleObject(): void
    {
        $userPasswordHasher = $this->getMockBuilder(UserPasswordHasher::class)->disableOriginalConstructor()->getMock();
        $userPasswordHasher->expects($this->never())->method('hashPassword');

        $processor = new UserProcessor($userPasswordHasher);
        $processor->preProcess('user', new Article());
    }

    public function testPreProcessWithUserObject(): void
    {
        $userPasswordHasher = $this->getMockBuilder(UserPasswordHasher::class)->disableOriginalConstructor()->getMock();
        $userPasswordHasher->expects($this->once())->method('hashPassword')->willReturn('hashed_password');

        $user = new User();
        $user->setPassword('password');

        $processor = new UserProcessor($userPasswordHasher);
        $processor->preProcess('user', $user);

        $this->assertSame('hashed_password', $user->getPassword());
    }
}
