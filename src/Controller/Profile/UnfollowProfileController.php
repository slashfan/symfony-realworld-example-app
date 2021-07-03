<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Controller\AbstractController;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}/follow", methods={"DELETE"}, name="api_profiles_unfollow")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class UnfollowProfileController extends AbstractController
{
    public function __invoke(User $profile): array
    {
        $user = $this->getCurrentUser();
        $user->unfollow($profile);

        $this->getDoctrine()->getManager()->flush();

        return ['profile' => $profile];
    }
}
