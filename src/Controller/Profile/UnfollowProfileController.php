<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Entity\User;
use App\Security\UserResolver;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}/follow", methods={"DELETE"}, name="api_profiles_unfollow")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UnfollowProfileController
{
    private \Doctrine\ORM\EntityManagerInterface $entityManager;

    private \App\Security\UserResolver $userResolver;

    public function __construct(EntityManagerInterface $entityManager, UserResolver $userResolver)
    {
        $this->entityManager = $entityManager;
        $this->userResolver = $userResolver;
    }

    public function __invoke(User $profile): array
    {
        $user = $this->userResolver->getCurrentUser();
        $user->unfollow($profile);
        $this->entityManager->flush();

        return ['profile' => $profile];
    }
}
