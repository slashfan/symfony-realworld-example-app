<?php

namespace App\Controller\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/api/profiles/{username}/follow", name="api_profiles_unfollow")
 * @Method("DELETE")
 */
class ProfilesUnfollowController
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

        $user->unfollow($profile);
        $this->manager->flush();

        return ['profile' => $profile];
    }
}
