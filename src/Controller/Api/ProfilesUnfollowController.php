<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}/follow", name="api_profiles_unfollow")
 * @Method("DELETE")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class ProfilesUnfollowController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserResolver
     */
    private $userResolver;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserResolver           $userResolver
     */
    public function __construct(EntityManagerInterface $entityManager, UserResolver $userResolver)
    {
        $this->entityManager = $entityManager;
        $this->userResolver = $userResolver;
    }

    /**
     * @param User $profile
     *
     * @throws \Exception
     *
     * @return array
     */
    public function __invoke(User $profile)
    {
        $user = $this->userResolver->getCurrentUser();
        $user->unfollow($profile);
        $this->entityManager->flush();

        return ['profile' => $profile];
    }
}
