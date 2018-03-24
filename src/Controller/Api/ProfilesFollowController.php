<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * ProfilesFollowController.
 *
 * @Route("/api/profiles/{username}/follow", name="api_profiles_follow")
 * @Method("POST")
 */
class ProfilesFollowController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param UserInterface $user
     * @param User          $profile
     *
     * @return array
     */
    public function __invoke(UserInterface $user, User $profile)
    {
        /* @var User $user */

        $user->follow($profile);
        $this->manager->flush();

        return ['profile' => $profile];
    }
}
