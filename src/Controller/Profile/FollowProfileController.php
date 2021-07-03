<?php

declare(strict_types=1);

namespace App\Controller\Profile;

use App\Controller\AbstractController;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/profiles/{username}/follow", methods={"POST"}, name="api_profiles_follow")
 *
 * @Security("is_granted('ROLE_USER')")
 */
final class FollowProfileController extends AbstractController
{
    public function __invoke(User $profile): array
    {
        $user = $this->getCurrentUser();
        $user->follow($profile);

        $this->getDoctrine()->getManager()->flush();

        return ['profile' => $profile];
    }
}
