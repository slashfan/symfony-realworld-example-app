<?php

declare(strict_types=1);

namespace App\DataFixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserProcessor implements ProcessorInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $id, object $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        $object->setPassword($this->userPasswordHasher->hashPassword($object, $object->getPassword()));
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $id, object $object): void
    {
        // nothing to do
    }
}
