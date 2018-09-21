<?php

declare(strict_types=1);

namespace App\DataFixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * UserPasswordProcessor.
 */
final class UserProcessor implements ProcessorInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $fixtureId, $object): void
    {
        /** @var User $object * */
        if (false === $object instanceof User) {
            return;
        }

        $object->setPassword($this->encoder->encodePassword($object, $object->getPassword()));
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $fixtureId, $object): void
    {
        // nothing to do
    }
}
