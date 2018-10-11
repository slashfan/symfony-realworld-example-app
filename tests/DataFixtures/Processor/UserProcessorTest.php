<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures\Processor;

use App\DataFixtures\Processor\UserProcessor;
use App\Entity\Article;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UserProcessorTest.
 */
class UserProcessorTest extends TestCase
{
    public function testPreProcessWithArticleObject(): void
    {
        $encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $encoder->expects($this->never())->method('encodePassword');

        /** @var UserPasswordEncoderInterface $encoder */
        $processor = new UserProcessor($encoder);
        $processor->preProcess('user', new Article());
    }

    public function testPreProcessWithUserObject(): void
    {
        $encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)->getMock();
        $encoder->expects($this->once())->method('encodePassword')->willReturn('encoded_password');

        $user = new User();
        $user->setPassword('password');

        /** @var UserPasswordEncoderInterface $encoder */
        $processor = new UserProcessor($encoder);
        $processor->preProcess('user', $user);

        $this->assertSame('encoded_password', $user->getPassword());
    }
}
