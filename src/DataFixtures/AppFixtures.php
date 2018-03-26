<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $objectSet = (new NativeLoader())->loadFile(__DIR__.'/../../fixtures/data.yaml');

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof User) {
                $object->setPassword($this->passwordEncoder->encodePassword($object, $object->getPassword()));
            }
            $manager->persist($object);
        }

        $manager->flush();
    }
}
